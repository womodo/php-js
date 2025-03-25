import requests
import json

def send_message(url, msg):
    # POSTリクエストを送信
    response = requests.post(
        url=url,
        data=json.dumps(msg),
        headers={"Content-Type": "application/json"}
    )
    return response

if __name__ == '__main__':
    url = "x"
    title = "タイトル"
    text = "＊＊＊メッセージ欄＊＊＊テスト"

    msg = {
        "attachments":[
            {           
                "contentType":"application/vnd.microsoft.card.adaptive",
                "content":{                                             
                    "$schema":"http://adaptivecards.io/schemas/adaptive-card.json",
                    "type":"AdaptiveCard",
                    "version":"1.2",
                    "msteams": { "width": "full" },
                    "body":[
                        {
                            "type":"TextBlock",
                            "text":"This is notification",
                            "color":"warning"
                        }                   
                    ]   
                }   
            }   
        ]   
    }
    response = send_message(url, msg)

    # レスポンスを確認
    if response.status_code == 200:
        print("メッセージを送信しました")
    elif response.status_code == 202:
        print("メッセージの送信を受付ました")
    else:
        print(f"エラーが発生しました: {response.status_code}, {response.text}")

