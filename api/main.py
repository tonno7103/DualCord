import secrets
from datetime import timedelta

from fastapi import FastAPI, Query, HTTPException, Depends
from fastapi.middleware.cors import CORSMiddleware
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from jose import JWTError, jwt
from passlib.context import CryptContext
from starlette import status

import database
from Models.RequestModels import *
from Models.ResponseModels import *

app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],

)

SECRET_KEY = secrets.token_bytes(256)
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 1440

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="token")


class TokenData(BaseModel):
    user_email: Union[str, None] = None


class Token(BaseModel):
    access_token: str
    token_type: str


# Hashing and verifing password
def verify_password(plain_password, hashed_password):
    return pwd_context.verify(plain_password, hashed_password)


def get_password_hash(password):
    return pwd_context.hash(password)


# Authentication
def authenticate_user(user_id: int, password: str):
    user = database.get_user_by_email(user_id)
    if not user:
        return False
    if not verify_password(password, user.password):
        return False

    return user


def create_access_token(data: dict, expires_delta: Union[timedelta, None] = None):
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.datetime.utcnow() + expires_delta
    else:
        expire = datetime.datetime.utcnow() + timedelta(minutes=15)
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt


async def get_current_active_user(token: str = Depends(oauth2_scheme)):
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Could not validate credentials",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        user_email: str = payload.get("sub")
        if user_email is None:
            raise credentials_exception
        token_data = TokenData(user_email=user_email)
    except JWTError:
        raise credentials_exception
    user = database.get_user_by_email(token_data.user_email)
    if user is None:
        raise credentials_exception
    return user


@app.post("/token", response_model=Token)
async def login_for_access_token(form_data: OAuth2PasswordRequestForm = Depends()):
    user = authenticate_user(form_data.username, form_data.password)
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect username or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    access_token_expires = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    access_token = create_access_token(
        data={"sub": user.email}, expires_delta=access_token_expires
    )
    return {"access_token": access_token, "token_type": "bearer"}


@app.get("/user", response_model=Union[ResponseSearchUser, None])
async def root(
        user_id: int = Query(default=None, description="The user id"),
        user_email: str = Query(default=None, min_length=5, description="The user email"),
        user_tag: str = Query(default=None, description="The user tag"),
):
    if user_id:
        user = database.get_user_by_id(user_id)
        return user
    elif user_email and user_tag is None:
        user = database.get_user_by_email(user_email)
        return user
    elif user_tag and user_email is None:
        user = database.get_user_by_tag(user_tag)
        return user
    else:
        raise HTTPException(status_code=400, detail={
            "error": "Invalid query",
            "description": "You must specify only one query parameter (user_id, user_email, user_tag)"
        })


@app.post("/register", response_model=ResponseCreateUser)
async def root(body: RequestCreateUser):
    user = database.get_user_by_email(body.email)
    if user:
        raise HTTPException(status_code=400, detail={
            "error": "User already exists",
            "description": "The user with email {} already exists".format(body.email)
        })
    user = database.get_user_by_tag(body.tag)
    if user:
        raise HTTPException(status_code=400, detail={
            "error": "User already exists",
            "description": "The user with tag {} already exists".format(body.tag)
        })

    password = get_password_hash(body.password)
    user = database.User.create(
        username=body.username,
        tag=body.tag,
        password=password,
        email=body.email
    )

    return user


@app.get("/user/me", response_model=ResponseSearchUser)
async def read_users_me(current_user: ResponseSearchUser = Depends(get_current_active_user)):
    return current_user


@app.on_event("startup")
async def startup_event():
    print("Starting up...")
    database.db.connect()
    print("Done!")


@app.on_event("shutdown")
async def shutdown_event():
    print("Shutting down...")
    database.db.close()
    print("Done!")
