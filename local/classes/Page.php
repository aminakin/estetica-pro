<?
use CSite;

class Page
{
    public static function isMain()
    {
        global $APPLICATION;
        return ($APPLICATION->GetCurPage(false) === SITE_DIR);
    }

    public static function isNewsDetail()
    {
        global $APPLICATION;
        return (preg_match('#^/news/#', $APPLICATION->GetCurDir()));
    }

    public static function isSaleDetail()
    {
        global $APPLICATION;
        return (preg_match('#^/sale/#', $APPLICATION->GetCurDir()));
    }

    public static function isPersonal()
    {
        global $APPLICATION;
        return (preg_match('#^/personal/#', $APPLICATION->GetCurDir()));
    }

    public static function isCourse()
    {
        global $APPLICATION;
        return (preg_match('#^/courses/#', $APPLICATION->GetCurDir()));
    }

    public static function isCatalogDetail()
    {
        global $APPLICATION;
        $url =  $APPLICATION->GetCurDir();
        if (!preg_match('/filter/U', $url)) {
            if (preg_match('#^/catalog/#', $url)) {
                if (count(explode('/', $url)) > 4) return true;
            }
        }
        return false;
    }
}