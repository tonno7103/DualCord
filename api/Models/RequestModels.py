import pydantic


class BaseModel(pydantic.BaseModel):
    class Config:
        orm_mode = True


class RequestCreateUser(BaseModel):
    username: str
    tag: str
    password: str
    email: str


class OAuth2PasswordRequest(BaseModel):
    email: str
    password: str


class OAuth2VerifyRequest(BaseModel):
    jwt_token: str
