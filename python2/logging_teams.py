import logging
import requests
import json

FLOW_URL = 'Workflows URL'

class TeamsLoggingHandler(logging.Handler):
    def __init__(self, flow_url):
        super().__init__()
        self.flow_url = flow_url
    
    def emit(self, record):
        log_entry = self.format(record)
        print(record)

        try:
            msg = {
                "attachments": [
                    {
                        "contentType": "application/vnd.microsoft.card.adaptive",
                        "content": 
                            {
                                "$schema": "http://adaptivecards.io/schemas/adaptive-card.json",
                                "type": "AdaptiveCard",
                                "version": "1.5",
                                "msteams": {
                                    "width": "full"
                                },
                                "body": [
                                    {
                                        "type": "Container",
                                        "items": [
                                            {
                                                "type": "TextBlock",
                                                "text": "処理でエラーが発生しました😭",
                                                "weight": "bolder",
                                                "size": "medium",
                                                "style": "heading"
                                            },
                                            {
                                                "type": "TextBlock",
                                                "text": log_entry,
                                                "wrap": True
                                            },
                                            {
                                                "type": "TextBlock",
                                                "text": "[Google](https://www.google.com)",
                                                "wrap": True,
                                                "separator": True
                                            },
                                            {
                                                "type": "ActionSet",
                                                "actions": [
                                                    {
                                                        "type": "Action.OpenUrl",
                                                        "title": "EXPORT AS PDF",
                                                        "url": "https://adaptivecards.io"
                                                    }
                                                ]
                                            }
                                        ]
                                    },
                                ]
                            }
                    }
                ]
            }
            response = requests.post(
                url=self.flow_url,
                data=json.dumps(msg),
                headers={"Content-Type": "application/json"}
            )

            # レスポンスのステータスコードをチェック
            if response.status_code == 202:
                print("✅ リクエスト受理 (202 Accepted) → Power Automate 側で処理中")
            elif response.status_code != 200:
                print(f"⚠️ エラー: {response.status_code}, レスポンス: {response.text}")
            
        except Exception as e:
            print(f"❌ 送信エラー: {e}")

# ロガーの設定
logger = logging.getLogger("TeamsLogger")
logger.setLevel(logging.ERROR)
teams_handler = TeamsLoggingHandler(FLOW_URL)
teams_handler.setFormatter(logging.Formatter("%(asctime)s - %(levelname)s - %(message)s"))
logger.addHandler(teams_handler)

logger.debug('デバッグテスト')
logger.error('Power Automateを使ったTeams通知のテストメッセージです')

try:
    1 / 0
except:
    logger.exception('例外発生')