$(function () {
	/**
	 * Настройки Ajax
	 */
	$.ajaxSetup({
		cache: true,
		beforeSend: function () {
			loading();
		},
		complete: function () {
			loading(false);
		}
	});

	/**
	 * Скрывает сообщение
	 */
	$('body').on('click', '#hide_message', function () {
		hideMessage();
	});

	/**
	 * Обработка SuperJsonGrid
	 */
	if ($('.index-grid-view').length) {
		// Небольшой костыль, который назначает элементам шапки
		// таблици те же классы, что и у аналогичных ячеек тела таблицы
		var grid = $('.index-grid-view').first();
		var head_rows = grid.find('thead>tr');
		var head = head_rows.find('th');
		var filter = grid.find('.filters>td');
		var row = grid.find('tbody>tr').first().find('td');
		console.log(row);
		if (row.length > 1) {
			row.each(function (index) {
				$(head[index]).addClass($(this).attr('class'));
				$(filter[index]).addClass($(this).attr('class'));
			})
		}
		else {
			$(head).each(function (index) {
				if (index == 0) {
					$(this).addClass('grid_id_column');
				}
				else {
					$(this).addClass('grid_middle_column');
				}

			})
			$(filter).each(function (index) {
				if (index == 0) {
					$(this).addClass('grid_id_column');
				}
				else {
					$(this).addClass('grid_middle_column');
				}
			})
		}

		var all_height = $('html').height();
		all_height -= 80;
		all_height -= $('.index-grid-view tbody').offset().top;
		$('.index-grid-view tbody').height(all_height);
		$('.index-grid-view tbody').width($('.index-grid-view tr').first().width());

		/**
		 * Обработка нажатия кнопки "Отображать по"
		 */
		$('body').on('click', '.pageSize', function () {
			$('.pageSize').removeClass('active');
			$(this).addClass('active');
			var grid_id = $(this).parent().attr('data-id');
			$.cookie(grid_id + '_pageSize', $(this).attr('rel'))
			$('#' + grid_id).yiiJsonGridView('update');
		});
	}
});