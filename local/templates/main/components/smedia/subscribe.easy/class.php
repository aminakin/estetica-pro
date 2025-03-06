<?php 
	namespace Smedia\Components;

	use \CBitrixComponent;
	use \Bitrix\Main\Localization\Loc;
	use \Bitrix\Main\Application;
	use \Bitrix\Main\Loader;
	use \CSubscription;

	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	/**
	 * Компонент для быстрой подписки на определенные в параметрах рубрики
	 */
	class SubscribeEasyComponent extends CBitrixComponent
	{
		/**
		 * Страница шаблона для вывода json-объекта результата
		 */
		const JSON_RESULT_PAGE = 'json';

		/**
		 * Формат подписки по умолчанию
		 */
		const DEFAULT_FORMAT = 'html';

		/**
		 * Объект HTTP-запроса
		 * @var Bitrix\Main\Request
		 */
		protected $request;

		/**
		 * Страница подключаемого шаблона
		 * @var string
		 */
		protected $templatePage = '';

		/**
		 * Список возможных ошибок
		 * @var array of string
		 */
		protected $errors;


		/**
		 * Подготовка параметров.
		 * Приводим параметр RUBRICS к массиву.
		 * @param array $params
		 * @return array
		 */
		public function onPrepareComponentParams($params)
		{
			$rubrics = array();

			if (is_array($params['RUBRICS'])) 
			{
				foreach ($params['RUBRICS'] as $rubId) 
				{
					$rubId = intval($rubId);
					if ($rubId > 0) {
						$rubrics[] = $rubId;
					}
				}
			}
			else
			{
				$rubId = intval($params['RUBRICS']);
				if ($rubId > 0) {
					$rubrics[] = $rubId;
				}
			}

			$params['RUBRICS'] = $rubrics;

			return $params;
		}


		/**
		 * Точка входа.
		 * @return void
		 */
		public function executeComponent()
		{
			if (empty($this->arParams['RUBRICS'])) {
				ShowError(Loc::getMessage('err_no_rubrics'));
				return;
			}

			Loader::includeModule('subscribe');
			$this->request = Application::getInstance()->getContext()->getRequest();

			if ($this->request->isPost()) {
				$this->handlePostRequest();
			}

			$this->includeComponentTemplate($this->templatePage);
		}


		/**
		 * Метод возвращает весь накопленный список ошибок
		 * @return array
		 */
		public function getErrors()
		{
			return $this->errors;
		}


		/**
		 * Метод возвращает последнюю ошибку из списка
		 * @return string
		 */
		public function getLastError()
		{
			return end($this->errors);
		}


		/**
		 * Обработка POST-запроса на подписку
		 * @return void
		 */
		protected function handlePostRequest()
		{
			global $USER;
			$this->templatePage = $this::JSON_RESULT_PAGE;

			if (!$this->checkInputParams()) return;

			$email = $this->request->getPost('email');

			$subscr = new CSubscription;

			$dbSubscr = CSubscription::GetByEmail($email);
			if ($existSubscr = $dbSubscr->Fetch())
			{
				// подписка существует - обновляем
				$existRubrics = CSubscription::GetRubricArray($existSubscr['ID']);
				$fields = array(
					'RUB_ID' => array_merge($existRubrics, $this->arParams['RUBRICS'])
				);

				if ($subscr->Update($existSubscr['ID'], $fields)) 
				{
					$this->arResult = array(
						'status' => 'ok',
						'message' => ''
					);
				}
				else
				{
					$this->arResult = array(
						'status' => 'error',
						'message' => $subscr->LAST_ERROR
					);
				}
			}
			else
			{
				// адреса в базе нет - добавляем
				$fields = array(
					'USER_ID' => $USER->IsAuthorized() ? $USER->GetID() : false,
					'FORMAT' => $this::DEFAULT_FORMAT,
					'EMAIL' => $email,
					'ACTIVE' => 'Y',
					'CONFIRMED' => 'Y',
					'RUB_ID' => $this->arParams['RUBRICS']
				);

				$subscrId = $subscr->Add($fields);
				if ($subscrId > 0) 
				{
					$this->arResult = array(
						'status' => 'ok',
						'message' => ''
					);
				}
				else
				{
					$this->arResult = array(
						'status' => 'error',
						'message' => $subscr->LAST_ERROR
					);
				}
			}
		}


		/**
		 * Валидация входных параметров
		 * @return bool
		 */
		protected function checkInputParams()
		{
			if (!check_bitrix_sessid()) {
				$this->arResult = array(
					'status' => 'error',
					'message' => Loc::getMessage('err_access')
				);
				return false;
			}

			if (empty($this->request->getPost('email'))) {
				$this->arResult = array(
					'status' => 'error',
					'message' => Loc::getMessage('err_empty_email')
				);
				return false;
			}

			if (!check_email($this->request->getPost('email'))) {
				$this->arResult = array(
					'status' => 'error',
					'message' => Loc::getMessage('err_incorrect_email')
				);
				return false;
			}

			return true;
		}
	}