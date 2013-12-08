// Сообщение, которое показывается по
// умолчанию при аяксовом запросе
var ajaxmessage = 'Загрузка';

/**
 * Устанавливает значение аяксового сообщения
 */
function setDefaultAjaxMessage() {
	ajaxmessage = 'Загрузка';
}

/**
 * Отправка данных в формате JSON
 * @param url экшн
 * @param data данные, которые нужно передать
 * @param onSuccess функция обратного вызова
 */
function simpleJson(url, data, onSuccess) {
	var dataJson = {};
	if (typeof data == 'object') {
		dataJson = data;
	}
	$.ajax({
		type: "POST",
		dataType: "json",
		url: url,
		data: dataJson,
		success: function (data) {
			if (data.redirect) {
				hideMessage();
				window.location.href = data.redirect;
			}
			else if (data.status == 200) {
				if (onSuccess) {
					onSuccess.call(data);
				}
				else {
					showMessage('success', data.message);
				}
			}
			else {
				showMessage('error', data.message);
			}
		}
	});
}

function ajaxForm(id_form, ajaxMessage, onSuccess) {
	if (ajaxMessage) {
		ajaxmessage = ajaxMessage;
	}
	$("#" + id_form).ajaxForm({
		dataType: 'json',
		success: function (data) {
			if (data.redirect) {
				hideMessage();
				window.location.href = data.redirect;
			}
			else if (data.status == 200) {
				if (onSuccess) {
					onSuccess.call(data);
				}
				else {
					showMessage('success', data.message);
				}
			}
			else {
				showMessage('error', data.message);
			}

		}
	}).submit();
}

/**
 * Посылает на сервер запрос на удаление записей.
 * @param action
 * @param grid_id
 */
function deleteRecords(action, grid_id) {
	// Выбираем все отмеченые чекбоксы
	var records = $('#' + grid_id + ' td > :checked');
	var ids = [];// Формируем на их основе массив идентификаторов для удаления
	records.each(function () {
		ids.push($(this).val());
	});

	// Если есть элементы для удаления
	if (ids.length) {
		// Спрашиваем у пользователя действительно ли он хочет их удалить и
		if (confirm('Для удаления выбрано ' + ids.length + ' записей. Продолжить?')) {
			// Посылаем запрос на удаление записей.
			ajaxmessage = 'Удаление';
			simpleJson(action, {ids: ids}, function () {
				showMessage('success', this.message);
				records.each(function () {// Удаляем эти записи
					$(this).parent().parent().remove();
				});
			});
		}
		else {
			return false;
		}
	}
	else {
		showMessage('error', 'Не выбрано ни одной записи!');
	}
	//	return false;
}

/**
 * Загружает элемент данными с сервера
 *
 * @param element элемент например $("#my_element")
 * @param url экшн
 * @param data данные
 * @param onSuccess функция обратного вызова
 */
function ajaxLoad(element, url, data, onSuccess) {
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		success: function (data) {
			element.html(data);
			if (onSuccess) {
				onSuccess.call();
			}
		}
	});
}

/**
 * Показывает пользователю сообщение, ошибку или предупреждение
 *
 * @param {type} type - тип сообщения (успех, ошибка, предупреждение)
 * @param {type} text - собственно текст сообщения
 * @param {type} lifetime - время жизни сообщения (необязательно)
 */

// Общая переменная для бинда таймаута скрытия сообщения
var message_life;

// Общая переменная для текущего класса сообщения (чтобы можно было почистить)
var current_alert_class = '';

/**
 * Показывает сообщение системы
 * @param type - тип сообщения (ошибка, успех или предупреждение)
 * @param text - текст сообщения
 * @param lifetime - время жизни сообщения (количество секунд - сколько это сообщение будет показываться на экране)
 */
function showMessage(type, text, lifetime) {

	// первым делом чистим таймаут скрытия и
	// немедленно скрываем предыдущее сообщение на всякий случай
	clearTimeout(message_life);
	hideMessage();

	// если время жизни сообщения явно не указано
	// то оно живет 5 секунд
	if (lifetime) {
		var lifetime = lifetime * 1000;
	}
	else {
		var lifetime = 5 * 1000;
	}

	// сообщения могут быть 3-х типов
	// для стилизации сообщения используются классы Twitter Bootstrap
	var types = {
		confirmation: 'alert-info',
		warning: 'alert-warning',
		error: 'alert-error',
		success: 'alert-success'
	};
	// Записываем текущий класс в общую переменную
	current_alert_class = types[type];

	// Показываем сообщение
	$('#message_body').html(text).addClass(types[type]);
	$('#message_wrapper').show();

	// Ставим таймаут на скрытие сообщения
	message_life = setTimeout(function () {
		hideMessage();
	}, lifetime);

	$('#message_wrapper').hover(function () {
		// Убираем таймаут на скрытие, если мышка наведена на сообщение
		clearTimeout(message_life);
	});

	$('#message_wrapper').mouseleave(function () {
		// Ставим таймаут на скрытие сообщения
		message_life = setTimeout(function () {
			hideMessage();
		}, 2000);
	});
}

/**
 * Скрыватет сообщение с глаз долой
 */
function hideMessage() {
	$('#message_wrapper').hide();
	$('#message_body').removeClass(current_alert_class).html('');
}

/**
 * Показывает модальное окно
 * @param modal_id идентификатор модального окна
 */
function showModal(modal_id) {
	$('#messages_line').addClass('modal_mode');
	$('#' + modal_id).modal('show');
}

function hideModal(modal_id) {
	$('#messages_line').removeClass('modal_mode');
	$('#' + modal_id).modal('hide');
}

/**
 * loading()
 * показвает или скрывает индикатор загрузки
 * и добавляет в него текст указанный в переменной text
 *
 * Если в переменную text передано false - скрывает индикатор
 * загрузки и очищает текст
 */
function loading(text) {
	// Если в качестве параметра text передано false
	// скрываем индикатор загрузки. Так же индикатор загрузки
	// можно временно отключить присвоив ajaxmessage значение false
	if (text === false || ajaxmessage === false) {
		$('#loading_wrapper').hide();
		setDefaultAjaxMessage();
		clearInterval(loading_dots_interval);
		return true;
	}
	// Если же текст не передан или передана строка
	// Показываем индикатор загрузки
	var html = text ? text : ajaxmessage;
	// Показываем сообщение
	$('#loading_message').html(html);
	// Назначаем ширину блоку загрузки примерно равную ширине контента
	$('#loading_wrapper').css({'width': (
		html.length * 14
		) + 10});
	animateLoadingDots();
	$('#loading_wrapper').show();
	return true;
}

/**
 * Текущий текст точек
 */
var current_loading_dots;

/**
 * Внешняя переменная для интервала, чтобы можно было оборвать
 */
var loading_dots_interval;

/**
 * Запускает анимацию точек при загрузке
 */
function animateLoadingDots() {
	current_loading_dots = '';
	loading_dots_interval = setInterval(function () {
		$('#loading_dots').text(current_loading_dots);
		current_loading_dots += '.';
		if (current_loading_dots.length > 3) {
			current_loading_dots = ''
		}
	}, 450);
}
animateLoadingDots();

function unsetGridFilterCookies(grid_id) {
	$.removeCookie(grid_id);
	$.fn.yiiGridView.update(grid_id, {data: {unsetFilter: 1}});
	return false;
}

/**
 * Записывает параметры тестера системы в кукисы
 * массив в котором хранятся пораметры идентичен массиву $_REQUEST[grid_id]
 * Чтобы получить куки в PHP нужно вызвать например $_COOKIE['grid_id']
 * @param expiries - если хочется, то можно задать время жизни (в днях) по умолчанию expiries = 7
 * @param path - если хочется, то можно задать путь по умолчанию path = '/'
 */
function setTesterParams() {
	var cookie = {};
	if ($('#tester_fake_role')) {
		cookie.tester_fake_role = $('#tester_fake_role').val()
	}
	// Пихаем всю эту кашу из топора в кукис и идем пить чай
	$.cookie('tester_params', JSON.stringify(cookie), { expires: 7, path: '/' });
	showMessage('success', 'Ваша роль успешно изменена на ' + $('#tester_fake_role').val());
	window.location.reload();
}
/**
 * Сбрасывает параметры тестера системы в кукисах
 */
function resetTesterParams() {
	// Пихаем всю эту кашу из топора в кукис и идем пить чай
	$.cookie('tester_params', '', { expires: -1, path: '/' });
	showMessage('success', 'Вы сбросили роль');
	window.location.reload();
}

/**
 * Записывает параметры фильтра таблицы в кукис
 * массив в котором хранятся пораметры идентичен массиву $_REQUEST[grid_id]
 * Чтобы получить куки в PHP нужно вызвать например $_COOKIE['grid_id']
 * @param grid_id - идентификатор таблицы, которую будем фильтровать (служит именем для кукис)
 * @param expiries - если хочется, то можно задать время жизни (в днях) по умолчанию expiries = 7
 * @param path - если хочется, то можно задать путь по умолчанию path = '/'
 */
function setGridFilterCookies(grid_id, expiries, path) {
	if (!grid_id) {// Если тупой программер забыл передать идентификатор - дохнем без зазрения совести!
		console.warn('Для записи фильтра нужно указать идентификатор таблицы!');
		return;
	}
	// Ставим дефолтные настройки времени жизни и пути
	var expiries = (
		expiries ? 7 : expiries
		);
	var path = (
		path ? '/' : path
		);

	// Малость костылево определяем пространство полей, которые будем слушать
	var listen = $('#' + grid_id + ' .filter-container input, #' + grid_id + ' .filter-container select');

	// Как только с каким-либо полем происходит смена значения
	listen.on('keyup change', function () {
		// начинаем танец с бубном (бубен можно взять в серверной)
		var cookie = {};
		listen.each(function () {
			// Получаем имя поля и его значение
			var name = $(this).attr("name");
			var value = $(this).val();
			// Если поле не пустое, то добавляем элемент в массив
			if (value && name) {
				// Чтобы массив значений на выходе был похож на $_REQUEST
				// нужно чуток подшаманить полученное имя поля
				name = name.replace(']', ',');
				name = name.replace('[', ',');
				name = name.split(',')[1];
				cookie[name] = value;
			}
		});

		// Пихаем всю эту кашу из топора в кукис и идем пить чай
		$.cookie(grid_id, JSON.stringify(cookie), { expires: expiries, path: path });
	});
}
/**
 * Чистит фильтр для конкретного грида (удаляет куку)
 * @param cookie - название куки (совпадает с id таблицы)
 */
function flushFilterData(grid_id) {
	$.removeCookie(grid_id);
	$.removeCookie(grid_id + '_pageSize');
	window.location.reload();
	return false;
}

/**
 * Обновляет грид
 * @param grid_id - идентификатор грида
 */
function updateGrid(grid_id) {
	$('#' + grid_id).yiiJsonGridView('update');
}

/**
 *
 * @param grid_id - идентификатор таблицы, которую будем фильтровать (он же имя кукис)
 * @param multiselects - список мультиселектов для реинсталла (объект вида ключ : значение,
 *                         где ключ - это идентификатор мультиселекта, а значение - это его атрибут модели
 */
function reinstallMultiSelectFromCookies(grid_id, multiselects) {
	for (var key in multiselects) {
		var id = $('#' + key);
		if (!id.html()) {
			continue;
		}// Если не найден такой мультиселект, то чешем дальше
		id.select2();// Если же таковой имеется, то реинсталируем его
		var cookie_data = $.cookie(grid_id);
		if (cookie_data == undefined) {
			continue;
		}
		var cookie_as_array = JSON.parse($.cookie(grid_id));// Распарсиваем фильтр этой таблицы (который берем из кукис)
		if (!cookie_as_array) {
			continue;
		}// Если нашего поля в кукисах не хранится то прыгаем на следующий луп
		if (cookie_as_array[multiselects[key]]) {
			id.select2('val', cookie_as_array[multiselects[key]]);// Если есть, то пихаем в select2 значение этог поля
		}
	}
}

/**
 *
 * @param grid_id - идентификатор таблицы, которую будем фильтровать (он же имя кукис)
 * @param datepickers - список для реинсталла (объект вида ключ : значение,
 *                         где ключ - это идентификатор, а значение - это его атрибут модели
 */
function reinstallDatePickerFromCookies(grid_id, datepickers) {
	for (var key in datepickers) {
		var id = $('#' + key);
		if (!id) {
			continue;
		}// Если не найден такой мультиселект, то чешем дальше
		id.datepicker({format: 'dd.mm.yyyy'});// Если же таковой имеется, то реинсталируем его
		var cookie_data = $.cookie(grid_id);
		if (cookie_data == undefined) {
			continue;
		}
		var cookie_as_array = JSON.parse($.cookie(grid_id));// Распарсиваем фильтр этой таблицы (который берем из кукис)
		if (!cookie_as_array) {
			continue;
		}// Если нашего поля в кукисах не хранится то прыгаем на следующий луп
		if (cookie_as_array[datepickers[key]]) {
			id.val(cookie_as_array[datepickers[key]]);// Если есть, то пихаем в datepicker значение этог поля
		}
	}
}

/**
 * Обрабатывает массив чекбоксов пользовательского интерфейса управления правами пользователя
 * превращает их в небольшой массив данных для передачи на сервер
 * @param actionUrl - экшн контроллера, который будет обрабатывать запрос
 * @param userId - идентификатор клиента
 * @param flush {boolean} - если true - сбрасывает права доступа на сервере
 * @returns {boolean} false
 */
function savePersonalAccessRules(actionUrl, userId, flush) {
	if (flush) {
		if (!confirm('Сбросить права пользователя?')) {
			return false;
		}
	}
	var allowed = [];
	var forbidden = [];
	$('#all_user_permissions :checkbox').each(function () {
		if ($(this).prop('checked') && $(this).attr('data') == 'personal') {
			allowed.push($(this).val())
		}
		if (!$(this).prop('checked') && $(this).attr('data') == 'default') {
			forbidden.push($(this).val())
		}
		if (flush && $(this).attr('data') == 'default') {
			$(this).prop('checked', true);
		}
	});
	allowed = allowed.join();
	forbidden = forbidden.join();
	var data = {
		UserPersonalAccessRules: {
			user_id: userId,
			allowed: flush ? null : allowed,
			forbidden: flush ? null : forbidden
		}
	};
	simpleJson(actionUrl, data);
}

/**
 * Сохраняет выбранную роль в базе данных
 * @param actionUrl - экшн контроллера, который будет обрабатывать запрос
 * @param userId - идентификатор клиента
 * @returns {boolean} false
 */
function saveUserRole(actionUrl, userId) {
	var data = {
		User: {
			user_id: userId,
			role: $('#user_role').val()
		}
	};
	simpleJson(actionUrl, data);
}

/**
 * Обработчик кнопки вызова аяксового
 * модального окна для просмотра записи
 * @param button
 */
function gridAjaxViewButton(button) {
	var id = button.attr("data-id");
	var controller = button.attr("data-controller");
	var modal_id = 'view_' + controller + '_modal';
	simpleJson('/' + controller + '/view/' + id, {}, function () {
		$('#' + modal_id).html(this.html);
		showModal(modal_id);
	});
	return false;
}

/**
 * Обработчик кнопки удаления записи в таблице
 * @param button
 */
function gridAjaxDeleteButton(button) {
	var id = button.attr("data-id");
	var controller = button.attr("data-controller");
	var grid_id = button.attr("data-grid");
	if (confirm('Вы действительно хотите удалить эту запись?')) {
		ajaxmessage = 'Удаление';
		simpleJson('/' + controller + '/delete/' + id, {}, function () {
			showMessage('success', this.message);
			updateGrid(grid_id);
		});
	}
	return false;
}

/**
 *  Функция закрытия текущего модального окна с ссобщением
 *  по щелчку на кнопку
 * @param btn - объект самой кнопки, на которую щелкнули
 * @param confirm_text - текст подтверждения закрытия или просто TRUE для стандартного текста.
 * @returns {boolean}
 */
function closeModal(btn, confirm_text) {
	var modal_window = $($(btn).closest("div.modal").get(0));
	if (confirm_text) {
		var message = confirm_text === true ? "Окно закроется и все несохраненные данные пропадут. Продолжить?" : confirm_text;
		if (!confirm(message)) {
			return false;
		}
	}
	$('#messages_line').removeClass('modal_mode');
	modal_window.modal("hide");
	return false;
}