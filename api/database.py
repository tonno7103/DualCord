import peewee

db = peewee.SqliteDatabase("db.sqlite3")


class BaseModel(peewee.Model):
    class Meta:
        database = db


class User(BaseModel):
    username = peewee.CharField()
    tag = peewee.CharField(unique=True)
    password = peewee.CharField()
    email = peewee.CharField(unique=True)
    image = peewee.BlobField(null=True)
    created_at = peewee.DateTimeField(default=peewee.datetime.datetime.now)
    updated_at = peewee.DateTimeField(default=peewee.datetime.datetime.now)


def create_tables():
    db.connect()
    db.create_tables([User])
    db.close()


def get_user_by_tag(tag: str):
    user = User.get_or_none(User.tag == tag)
    return user


def get_user_by_email(email: str):
    user = User.get_or_none(User.email == email)
    return user


def get_user_by_id(id_user: int):
    user = User.get_or_none(User.id == id_user)
    return user


def connect():
    db.connect()
