from selenium import webdriver
from selenium.webdriver.chrome.options import Options
import time
from playsound import playsound

options = Options()
options.add_argument('--start-maximized')                                     # 初期のウィンドウサイズを最大化
options.add_experimental_option("excludeSwitches", ['enable-automation'])     # Chromeは自動テスト ソフトウェア~~ を非表示

# ------ ChromeDriver の起動 ------
driver = webdriver.Chrome(options=options)
driver.get('https://www.google.co.jp')


# 定期的にリロードを繰り返す
count = 0
while True:
    count += 1
    if (count == 3):
        playsound('se.mp3') # 音を鳴らす
    driver.refresh()    # ページをリロード
    time.sleep(5)       # 60秒ごとにリロードする
