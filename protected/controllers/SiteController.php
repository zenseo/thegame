<?php

class SiteController extends Controller
{

	/**
	 * Регистрация пользователя
	 */
	public function actionRegister()
	{

	}


	/**
	 * Набивает базу пользователями
	 */
	public function actionGenerateTestData()
	{
		$config = array(
			'generate_users' => 100,
			'generate_clients' => 100,
			'generate_contacts' => true,
		);
		$generator = new TestDataGenerator();



//		$begin = microtime();
//		$people = $generator->generateMan($config['generate_users']);
//		$speed_php = microtime() - $begin;
//		User::model()->deleteAll();
//		$begin = microtime();
//		Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('user', $people)->execute();
//		$speed_sql = microtime() - $begin;
//		echo 'Сгенерировано '.$config['generate_users'].' ползователей <br/>';
//		echo 'PHP - ' . $speed_php . ' ceк. MySql - ' . $speed_sql . ' сек.<br/>';


		$begin = microtime();
		$clients = $generator->generateClient($config['generate_clients']);
		$speed_php = microtime() - $begin;
		Customer::model()->deleteAll();
		$begin = microtime();
		Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('customer', $clients)->execute();
		$speed_sql = microtime() - $begin;

		echo 'Сгенерировано '.$config['generate_clients'].' клиентов <br/>';
		echo 'PHP - ' . $speed_php . ' ceк. MySql - ' . $speed_sql . ' сек.<br/>';
		if($config['generate_contacts']){
			$begin = microtime();
			Contact::model()->deleteAll();
			$contacts = $generator->generateContacts();
			$speed_php = microtime() - $begin;
			$begin = microtime();
			Yii::app()->db->schema->commandBuilder->createMultipleInsertCommand('contact', $contacts)->execute();
			$speed_sql = microtime() - $begin;
			echo 'Сгенерировано '.($config['generate_clients'] * 10).' контактов <br/>';
			echo 'PHP - ' . $speed_php . ' ceк. MySql - ' . $speed_sql . ' сек.';
		}


	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			}
			else {
				$this->render('error', $error);
			}
		}
	}

	public function actionLogin()
	{
		$this->layout = 'auth';
		$model = new LoginForm;
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login_form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login()) {
				$this->redirect('/');
			}
		}
		$this->render('login', array('model' => $model));
	}


	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionTest()
	{
		if (isset($_POST['User'])) {
			$this->checkAccess('changeUserIdTest');
			$model = new LoginForm();
			$model->attributes = $_POST['User'];
			if ($model->fakeLogin()) {
				$this->redirect('index');
			}
		}
		$this->checkAccess('panelTest');
		$this->render('test');
	}

	public function actionBugReport()
	{
		if (isset($_POST['BugReport']) && !empty($_POST['BugReport']['message'])) {
			$message = 'Пользователь ' . $_POST['BugReport']['username'] . ' нашел ошибку!<br/>
			ID пользоваетля - ' . $_POST['BugReport']['user_id'] . ', роль - ' . $_POST['BugReport']['user_role'] . '<br/>
			Сообщение:<br/>' . $_POST['BugReport']['message'];
			$from = Yii::app()->user->name;
			$to = array(
				'agilovr@gmail.com'
			);
			$body = array(
				'Body' => $message
			);
			if ($this->SendEmail($to, 'simple', 'simple', $body, 'Сообщение об ошибке', $from, Yii::app()->user->fullname)) {
				Yii::app()->user->setFlash('success', '<h1>Все супер!</h1> <p>Вы успешно отправили сообщение об ошибке!</p> ');
				$this->redirect('index');
			}
			else {
				Yii::app()->user->setFlash('error', '<h1>Что-то не так!</h1> <p>Сообщение об ошибке не отправлено! Обратитесь в отдел WEB-разработки.</p> ');
				$this->redirect('index');
			}
		}
		$this->render('bug_report');
	}
}
