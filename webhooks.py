from flask import Flask,request,json
import requests
from datetime import datetime
import time

server = "http://127.0.0.1:8096";
api_key='XXXXXXXXX';

app = Flask(__name__)

@app.route('/jellyfin', methods=['POST'])
def index():
   event_data = json.loads(request.get_data())
   user = event_data['NotificationUsername']
   device = event_data['DeviceId']
   action = event_data['NotificationType']
   x = requests.get(server+"/Sessions?api_key="+api_key+"&deviceId="+device)
   big_data = x.json()
   session = big_data[0]["Id"]
   print(user+" with device_id: "+device+" start session_id: "+session+" with action: "+action)
   if (action == "PlaybackStart"):
                  url = server+"/Sessions/"+session+"/Message?&api_key="+api_key
                  Text="Example of message: "
                  body = {"Text":Text, "Header":"Warning", "TimeoutMs": 20000}
                  x = requests.post(url, json = body)
   return ''

if __name__ == '__main__':
    app.run()
