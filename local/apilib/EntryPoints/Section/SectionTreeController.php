<?php

namespace ApiLib\EntryPoints\Section;

use ApiLib\API\Section\SectionTreeService;
use ApiLib\Response\Section\SectionTreeResponse;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;

class SectionTreeController extends Controller
{
    private SectionTreeService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new SectionTreeService();
    }

    public function index()
    {
        $sections = $this->service->index();

        return SectionTreeResponse::collection($sections);
    }
}