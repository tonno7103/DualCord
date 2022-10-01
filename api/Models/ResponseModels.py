import datetime
import pydantic

from typing import Union


class BaseModel(pydantic.BaseModel):
    class Config:
        orm_mode = True


class ResponseSearchUser(BaseModel):
    id: int
    username: str
    tag: str
    image: Union[bytes, None]


class ResponseCreateUser(BaseModel):
    id: int
    username: str
    tag: str
    email: str

    created_at: datetime.datetime
    updated_at: datetime.datetime
