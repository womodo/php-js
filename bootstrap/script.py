import sys

from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
import time

# WebDriverの初期化
browser = webdriver.Chrome()
# Googleを開く
browser.get("https://www.google.co.jp")
# 検索ボックスを探して検索ワードを入力
element = browser.find_element(By.CLASS_NAME, "gLFyf")
element.send_keys("Nuco")
element.send_keys(Keys.ENTER)

# スクリーンショットを取得し、保存する
browser.get_screenshot_as_file("C:\\Apache24\\htdocs\\php-js\\bootstrap\\test.png")

try:
    element = browser.find_element(By.NAME, "name")
except Exception as e:
    print(f"エラーが発生しました: {e}")
    browser.get_screenshot_as_file("C:\\Apache24\\htdocs\\php-js\\bootstrap\\error.png")
    pass

# コマンドライン引数の取得
name = sys.argv[1] if len(sys.argv) > 1 else "world"

# 実行結果を出力
print(f"Hello, {name}!")

with open('C:\\Apache24\\htdocs\\php-js\\bootstrap\\test.txt', 'a') as f:
    print('xxx', file=f)
