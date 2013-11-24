<?php
/**
 * Класс товара
 * Author: Роман Агилов <agilovr@gmail.com>
 * Date: 18.11.13
 * Time: 21:07
 */

class Product
{
	// Типы товаров (так к ним проще обращаться)
	const PRODUCT_TYPE_A = "A";
	const PRODUCT_TYPE_B = "B";
	const PRODUCT_TYPE_C = "C";
	const PRODUCT_TYPE_D = "D";
	const PRODUCT_TYPE_E = "E";
	const PRODUCT_TYPE_F = "F";
	const PRODUCT_TYPE_G = "G";
	const PRODUCT_TYPE_H = "H";
	const PRODUCT_TYPE_I = "I";
	const PRODUCT_TYPE_J = "J";
	const PRODUCT_TYPE_K = "K";
	const PRODUCT_TYPE_L = "L";
	const PRODUCT_TYPE_M = "M";
	const PRODUCT_TYPE_DUMMY = "DUMMY";

	/**
	 * @var string тип товара
	 */
	public $type;

	/**
	 * @var int итоговая стоимость товара с учетом скидки
	 */
	public $final_amount = 0;

	/**
	 * @var int полная стоимость товара
	 */
	public $full_amount = 0;

	/**
	 * @var string тестовая переменная для проверки отработки правил применения скидки
	 */
	public $discount_by_rule;

	/**
	 * Создает товар
	 * @param string $type тип товара
	 * @param int $full_amount полная цена товара
	 */
	public function __construct($type, $full_amount)
	{
		$this->type = $type;
		$this->full_amount = $full_amount;
	}

}

/**
 * Класс заказа товаров
 * Author: Роман Агилов <agilovr@gmail.com>
 * Date: 18.11.13
 * Time: 21:05
 */

class Order {
	/**
	 * @var array массив товаров заказа
	 */
	public $products = array();

	/**
	 * @var int полная стоимость всех товаров без учета скидки
	 */
	public $full_amount = 0;

	/**
	 * @var int итоговая стоимость заказа с учетом скидки
	 */
	public $final_amount = 0;

	/**
	 * @var int
	 */
	public $absolute_discount = 0;

	/**
	 * @var int относительная скидка в процентах
	 */
	public $rel_discount = 0;


}
/**
 * Калькулятор расчета заказа
 * Author: Роман Агилов <agilovr@gmail.com>
 * Date: 18.11.13
 * Time: 21:05
 */
class OrderCalculator
{
	/**
	 * @var array правила комбинаций товаров
	 * Комбинация => скидка за комбинацию
	 */
	public $sets_rules = array(
		'A,B' => 10,
		'D,E' => 5,
		'E,F,G' => 5,
		'A,K' => 5,
		'A,L' => 5,
		'A,M' => 5,
	);

	/**
	 * @var array правила количеств товаров
	 * Перечисляются все возможные варианты скидки
	 * по количествам товаов в порядке убывания
	 */
	public $count_rules = array(
		'counts' => array(
			5 => 20,
			4 => 10,
			3 => 5,
		),
		// Типы товаров, которые не входят в это правило
		'exceptions' => array(
			Product::PRODUCT_TYPE_A,
			Product::PRODUCT_TYPE_C,
		)
	);

	/**
	 * @var Order заказ товаров
	 */
	public $order;


	/**
	 * Создает товар
	 * @param Order $order заказ
	 * @throws Exception ругается, если передали пустой заказ
	 */
	public function __construct(Order $order)
	{
		if (empty($order->products)) {
			throw new Exception('Необходим заполненный товарами заказ!', 402);
		}
		$this->order = $order;
	}


	/**
	 * Расчитывает заказ на основе правил расчета
	 * @return Order
	 */
	public function calculateOrder()
	{
		/**
		 * @var $product Product
		 * @var $friend Product
		 * @var $second_friend Product
		 */
		$products = $this->order->products;
		$worked = array();

		// Обрабатываем правила сетов заданые в калькуляторе
		foreach ($this->sets_rules as $set => $discount) {
			// Преобразуем текстовое правило в массив
			$by_rule = $set;
			$set = explode(',', $set);

			if (count($set) == 2) { // Если наше правило двузначное
				// Проходимся циклом по всем товарам в надежде отыскать друзей =)
				for ($i = 0; $i < count($products); $i++) {
					$product = $products[$i];
					// Если товар попадает в правило
					if ($product->type == $set[0]) {
						// Пытаемся найти ему пару
						for ($j = 0; $j < count($products); $j++) {
							$friend = $products[$j];

							// Если нашли друга
							if ($friend->type == $set[1]) {
								// То вычисляем у каждого из них финальную цену относительно скидки
								$product->final_amount = $product->full_amount - ($product->full_amount * $discount / 100);
								$product->discount_by_rule = $by_rule;
								$friend->final_amount = $friend->full_amount - ($friend->full_amount * $discount / 100);
								$friend->discount_by_rule = $by_rule;

								// И отправляем сладкую парочку в массив отработанных товаров
								$worked[] = $product;
								$worked[] = $friend;

								// А на их место ставим "Дурачка" (для дураков скидка не предусмотрена)
								$dummy = new Product('DUMMY', 0);
								array_splice($products, $i, 1, array($dummy));
								array_splice($products, $j, 1, array($dummy));
								break;
							}
						}
					}
				}
			}
			else if(count($set) == 3) {

				// Проходимся циклом по всем товарам в надежде отыскать друзей =)
				for ($i = 0; $i < count($products); $i++) {
					$product = $products[$i];
					// Если товар попадает в правило
					if ($product->type == $set[0]) {
						// Пытаемся найти ему пару
						for ($j = 0; $j < count($products); $j++) {
							$friend = $products[$j];
							// Если нашли друга
							if ($friend->type === $set[1]) {
								for ($q = 0; $q < count($products); $q++) {
									$second_friend = $products[$q];
									// Если нашли еще друзей =)
									if ($second_friend->type === $set[2]) {
										// То вычисляем у каждого из них финальную цену относительно скидки
										$product->final_amount = $product->full_amount - ($product->full_amount * $discount / 100);
										$friend->final_amount = $friend->full_amount - ($friend->full_amount * $discount / 100);
										$second_friend->final_amount = $second_friend->full_amount - ($second_friend->full_amount * $discount / 100);

										$product->discount_by_rule = $by_rule;
										$friend->discount_by_rule = $by_rule;
										$second_friend->discount_by_rule = $by_rule;

										// И отправляем сладкую парочку в массив отработанных товаров
										$worked[] = $product;
										$worked[] = $friend;
										$worked[] = $second_friend;

										// А на их место ставим "Дурачка"
										// (для дураков скидка не предусмотрена)
										$dummy = new Product(Product::PRODUCT_TYPE_DUMMY, 0);
										array_splice($products, $i, 1, array($dummy));
										array_splice($products, $j, 1, array($dummy));
										array_splice($products, $q, 1, array($dummy));
										break;
									}
								}
								break;
							}
						}
					}
				}
			}
		}

		// Теперь когда сеты обработаны пришло время подвести итоги =)

		$total_free = 0; // без дураков =)
		foreach($products as $product){
			// Если товар не дурачок - добавляем его в отработанные, только без скидки
			if($product->type !== Product::PRODUCT_TYPE_DUMMY){
				// Если товар попадает в исключения - не увеличиваем счетчик
				if(in_array($product->type,$this->count_rules['exceptions'])){
					$product->final_amount = $product->full_amount;
					$worked[] = $product;
				}else{
					// Если все впорядке - инкрементируем счетчик и кидаем товар в отработанные
					$product->final_amount = $product->full_amount;
					$worked[] = $product;
					$total_free++;
				}
			}
		}

		// Проходим по массивчику правил количеств товара
		foreach($this->count_rules['counts'] as $count => $discount){
			// Если конечное количество свободных товаров равно или превышает
			// текущее проверяемое количество - ставим заказу общую скидку
			// и прекращаем проверку
			if($total_free >= $count){
				$this->order->rel_discount = $discount;
				break;
			}
		}

		// Складываем все стоимости товаров и получаем полную цену заказа
		foreach($worked as $product){
			$this->order->full_amount += $product->final_amount;
		}

		// Применяем относительную скидку к заказу
		if($this->order->rel_discount){
			$this->order->absolute_discount = $this->order->full_amount * $this->order->rel_discount / 100;
			$this->order->final_amount = $this->order->full_amount - $this->order->absolute_discount;
		}else{
			$this->order->final_amount = $this->order->full_amount;
		}
		$this->order->products = $worked;
		return $this->order;
	}
}

$products_for_test = array(
	// Проверим первое правило
	array(Product::PRODUCT_TYPE_A, 1000),
	array(Product::PRODUCT_TYPE_B, 1000),

	// Проверим второе правило
	array(Product::PRODUCT_TYPE_D, 1000),
	array(Product::PRODUCT_TYPE_E, 1000),

	// Проверим третье правило
	array(Product::PRODUCT_TYPE_E, 1000),
	array(Product::PRODUCT_TYPE_F, 1000),
	array(Product::PRODUCT_TYPE_G, 1000),


	// Проверим Четвертое правило
	array(Product::PRODUCT_TYPE_A, 1000),
	array(Product::PRODUCT_TYPE_K, 1000),

	array(Product::PRODUCT_TYPE_A, 1000),
	array(Product::PRODUCT_TYPE_L, 1000),

	array(Product::PRODUCT_TYPE_A, 1000),
	array(Product::PRODUCT_TYPE_M, 1000),


	// Проверим счетчик
	array(Product::PRODUCT_TYPE_H, 1000),
	array(Product::PRODUCT_TYPE_H, 1000),
	array(Product::PRODUCT_TYPE_H, 1000),
	array(Product::PRODUCT_TYPE_H, 1000),
	array(Product::PRODUCT_TYPE_J, 1000),

);
var_dump(count($products_for_test));

// Набиваем заказ товарами
$order = new Order();

foreach ($products_for_test as $product) {
	$order->products[] = new Product($product[0], $product[1]);
}

// Инициализация калькулятора
$calculator = new OrderCalculator($order);

// Счтитаем заказ
$order = $calculator->calculateOrder();
var_dump($order);