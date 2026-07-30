import requests
import json
import os
from dotenv import load_dotenv

load_dotenv()

base_url = os.getenv("CHATTER_URL")
username = os.getenv("CHATTER_USERNAME")

print("requests:", requests.__version__)
print("json imported successfully")
print(base_url)
print(username)