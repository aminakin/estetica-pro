<?php

namespace ApiLib\EntryPoints;

use Bitrix\Main\Application;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route as rrr;

class AController extends Controller
{
    private $checkAccess;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    public function setCheckAccess($array = [])
    {
        $this->checkAccess = $array;
    }

    public function addCheckAccess(string $path, string $rule): Controller
    {
        $this->checkAccess[$path][] = $rule;

        return $this;
    }

    public function checkAccess($rpage = '')
    {
        $request = $this->getRequest();
        if (empty($rpage))
            $rpage = Application::getInstance()->getCurrentRoute()->getUri();
        if (isset($this->checkAccess[$rpage])) {
            $this->runCheck($this->checkAccess[$rpage]);
        }
    }

    private function runCheck($rules)
    {
        foreach ($rules as $rule)
            (new $rule)->handle($this->request);
    }
}