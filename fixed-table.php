<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .container {
            overflow-x: auto;
            width: 100%;
            height: 300px;
        }

        #fixed-columns-table {
            border-collapse: collapse;
            border-spacing: 0;
            table-layout: fixed;
            width: 100%;
        }
        #fixed-columns-table th, td {
            border: 1px solid #ddd;
            padding: 5px;
            white-space: nowrap;
            width: 100px;
        }

        #fixed-columns-table th {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: white;
        }
        #fixed-columns-table td:nth-child(-n+3),
        #fixed-columns-table td:last-child {
            position: sticky;
            background-color: white;
            z-index: 2;
            border-left: none;
            border-right: none;
        }
        #fixed-columns-table th:nth-child(1),
        #fixed-columns-table td:nth-child(1) {
            left: 0;
        }
        #fixed-columns-table th:nth-child(2),
        #fixed-columns-table td:nth-child(2) {
            left: 111px;
        }
        #fixed-columns-table th:nth-child(3),
        #fixed-columns-table td:nth-child(3) {
            left: 222px;
        }
        #fixed-columns-table th:last-child,
        #fixed-columns-table td:last-child {
            right: 0;
        }
        #fixed-columns-table th:nth-child(-n+3),
        #fixed-columns-table th:last-child {
            position: sticky;
            background-color: bisque;
            z-index: 3;
            border-left: none;
            border-right: none;
        }

        #fixed-columns-table th:nth-child(-n+3)::before,
        #fixed-columns-table td:nth-child(-n+3)::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
            z-index: -1;
        }

        #fixed-columns-table tr:last-child td {
            background-color: white;
            position: sticky;
            bottom: 0;
        }
        #fixed-columns-table tr:last-child td::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-top-style: double;
            z-index: -1;
        }

        #fixed-columns-table tr:hover td {
            background-color: #e6f1ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <table id="fixed-columns-table">
            <thead>
                <tr>
                    <th>Colomun 1</th>
                    <th>Colomun 2</th>
                    <th>Colomun 3</th>
                    <th>Colomun 4</th>
                    <th>Colomun 5</th>
                    <th>Colomun 6</th>
                    <th>Colomun 7</th>
                    <th>Colomun 8</th>
                    <th>Colomun 9</th>
                    <th>Colomun 10</th>
                    <th>Colomun 11</th>
                    <th>Colomun 12</th>
                    <th>Colomun 13</th>
                    <th>Colomun 14</th>
                    <th>Colomun 15</th>
                    <th>Colomun 16</th>
                    <th>Colomun 17</th>
                    <th>Colomun 18</th>
                    <th>Colomun 19</th>
                    <th>Colomun 20</th>
                    <th>Colomun 21</th>
                    <th>Colomun 22</th>
                    <th>Colomun 23</th>
                    <th>Colomun 24</th>
                    <th>Colomun 25</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
                <tr>
                    <td>1列目</td>
                    <td>2列目</td>
                    <td>3列目</td>
                    <td>4列目</td>
                    <td>5列目</td>
                    <td>6列目</td>
                    <td>7列目</td>
                    <td>8列目</td>
                    <td>9列目</td>
                    <td>10列目</td>
                    <td>11列目</td>
                    <td>12列目</td>
                    <td>13列目</td>
                    <td>14列目</td>
                    <td>15列目</td>
                    <td>16列目</td>
                    <td>17列目</td>
                    <td>18列目</td>
                    <td>19列目</td>
                    <td>20列目</td>
                    <td>21列目</td>
                    <td>22列目</td>
                    <td>23列目</td>
                    <td>24列目</td>
                    <td>25列目</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>