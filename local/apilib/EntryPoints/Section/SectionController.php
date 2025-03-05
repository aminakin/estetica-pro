<?php

namespace ApiLib\EntryPoints\Section;

use ApiLib\API\Section\SectionService;
use ApiLib\Response\Section\SectionResponse;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;

class SectionController extends Controller
{
    private SectionService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new SectionService();
    }

    public function index(int $parent)
    {
        $sections = $this->service->index($parent);

        return SectionResponse::collection($sections);
    }

    public function show(int $id)
    {
        $section = $this->service->show($id);

        return SectionResponse::item($section);
    }
}