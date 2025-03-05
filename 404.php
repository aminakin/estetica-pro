<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("404 Страница не найдена");
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
?>
    <style>
        .title-header,
        nav.breadcrumbs{
            display: none;
        }
        .estetica-404{
            min-height: 515px;
            position: relative;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }
        .estetica-404__left-top-corner{
            position: absolute;
            left: 0;
            top: 3%;
        }
        .estetica-404__right-top-corner{
            position: absolute;
            top: 0;
            right: 0;
        }
        .estetica-404__left-bottom-corner{
            position: absolute;
            left: 7%;
            bottom: 7%;
        }
        .estetica-404__right-bottom-corner{
            position: absolute;
            right: 0;
            bottom: 0%;
        }

        .estetica-404__main-message{
            text-align: center;
        }
        .estetica-404__404{
            font-weight: 600;
            font-size: 96px;
            line-height: 131px;
            text-transform: uppercase;
            pointer-events: none;
            color: #EBCFD5;
        }
        .estetica-404__not-found{
            font-weight: bold;
            font-size: 18px;
            line-height: 18px;
            color: #EBCFD5;
            margin-bottom: 30px;
            pointer-events: none;
        }
        .estetica-404__text{
            font-size: 16px;
            line-height: 22px;
            margin-bottom: 30px;
            color: #808189;
            pointer-events: none;
        }
        .estetica-404__button a {
            display: inline-block;
            padding: 18px 89px;
            /* font-weight: bold; */
            font-size: 18px;
            line-height: 18px;
            /* color: #FFFFFF; */
            text-decoration: none;
            /* background: #EBCFD5; */
            border-radius: 27.5px;

            background: #ECCFD4;
            color: #221F20;
            font-weight: 500;
            transition: .2s all ease;
        }
        .estetica-404__button a:hover {
            background: #D8A9B2;
        }
    </style>
    <div class="estetica-404">
        <svg class="estetica-404__left-top-corner" width="75" height="68" viewBox="0 0 75 68" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M60.68 67.74H14.13C6.32 67.74 0 61.41 0 53.61V14.13C0 6.32 6.33 0 14.13 0H60.68C68.49 0 74.81 6.33 74.81 14.13V53.61C74.81 61.42 68.49 67.74 60.68 67.74Z" fill="#FBFBFB"/>
            <path d="M37.41 48.4695C28.67 48.4695 21.5601 41.3595 21.5601 32.6195C21.5601 23.8795 28.67 16.7695 37.41 16.7695C46.15 16.7695 53.2601 23.8795 53.2601 32.6195C53.2501 41.3595 46.14 48.4695 37.41 48.4695ZM37.41 21.5295C31.3 21.5295 26.3201 26.4995 26.3201 32.6195C26.3201 38.7295 31.29 43.7095 37.41 43.7095C43.5201 43.7095 48.5001 38.7395 48.5001 32.6195C48.4901 26.4995 43.5201 21.5295 37.41 21.5295Z" fill="#EBCFD5"/>
            <path d="M46.7501 21.5312L28.5701 43.7012" stroke="#EBCFD5" stroke-width="4.522" stroke-miterlimit="10"/>
        </svg>
        <svg class="estetica-404__right-top-corner" width="84" height="10" viewBox="0 0 84 10" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M79.08 9.06H4.53C2.03 9.06 0 7.03 0 4.53C0 2.03 2.03 0 4.53 0H79.08C81.58 0 83.61 2.03 83.61 4.53C83.61 7.03 81.58 9.06 79.08 9.06Z" fill="#EBCFD5"/>
        </svg>

        <div class="estetica-404__main-message">
            <div class="estetica-404__404">
                404
            </div>
            <div class="estetica-404__not-found">
                Page not found
            </div>
            <div class="estetica-404__text">
                Запрашиваемая<br />
                вами страница не найдена!
            </div>
            <div class="estetica-404__button">
                <a href="/catalog/">В каталог</a>
            </div>
        </div>

        <svg class="estetica-404__left-bottom-corner" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.59 13.56H3.40997C1.51997 13.56 0 12.03 0 10.15V3.40999C0 1.51999 1.52997 0 3.40997 0H10.59C12.48 0 14 1.52999 14 3.40999V10.15C14 12.03 12.47 13.56 10.59 13.56Z" fill="#EBCFD5"/>
        </svg>
        <svg class="estetica-404__right-bottom-corner" width="106" height="90" viewBox="0 0 106 90" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M93.24 89.82H12.24C5.48002 89.82 0 84.34 0 77.58V12.24C0 5.48 5.48002 0 12.24 0H93.24C100 0 105.48 5.48 105.48 12.24V77.58C105.48 84.34 100 89.82 93.24 89.82Z" fill="#FBFBFB"/>
            <path d="M46.0801 55.1319C36.0301 55.1319 27.8501 46.9519 27.8501 36.9019C27.8501 26.8519 36.0301 18.6719 46.0801 18.6719C56.1301 18.6719 64.3101 26.8519 64.3101 36.9019C64.3101 46.9519 56.1301 55.1319 46.0801 55.1319ZM46.0801 22.8119C38.3101 22.8119 31.9801 29.1319 31.9801 36.9119C31.9801 44.6819 38.3001 51.0119 46.0801 51.0119C53.8501 51.0119 60.1801 44.6919 60.1801 36.9119C60.1801 29.1319 53.8601 22.8119 46.0801 22.8119Z" fill="#EBCFD5"/>
            <path d="M58.9521 46.8961L56.0317 49.8164L61.4128 55.1974L64.3331 52.2771L58.9521 46.8961Z" fill="#EBCFD5"/>
            <path d="M67.1 51.5037L77.75 62.1538C78.88 63.2838 78.88 65.1137 77.75 66.2437L75.38 68.6137C74.25 69.7437 72.42 69.7437 71.29 68.6137L60.64 57.9637C59.51 56.8337 59.51 55.0037 60.64 53.8737L63.01 51.5037C64.14 50.3737 65.97 50.3737 67.1 51.5037Z" fill="#EBCFD5"/>
        </svg>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>