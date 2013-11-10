<?php
$this->pageTitle = Yii::app()->name . ' - Error | ' . $code;
$this->breadcrumbs = array(
	'Что-то пошло не так',
);
?>

<style type="text/css">

		/* Custom container */
	.container-narrow {
		margin: 0 auto;
		max-width: 700px;
	}

	.container-narrow > hr {
		margin: 30px 0;
	}

		/* Main marketing message and sign up button */
	.jumbotron {
		text-align: center;
	}

	.jumbotron h1 {
		font-size: 72px;
		line-height: 1;
	}

	.jumbotron .btn {
		font-size: 21px;
		padding: 14px 24px;
	}

	.marketing p + h4 {
		margin-top: 28px;
	}
</style>
<div style="text-align: center">
	<img src=/images/m.jpg>

	<?php if ($code == 403) { ?>

		<h2><?php echo $code; ?> &#151; <?php echo CHtml::encode($message); ?></h2>

	<?php
	}
	else {
		?>

		<div class="jumbotron">
			<h1>Упссс,<br>что-то пошло не так...</h1>
		</div>
		<hr>
		<small class="muted"><?php echo $code; ?> &#151; <?php echo CHtml::encode($message); ?></small></p>

	<?php } ?>
</div>




