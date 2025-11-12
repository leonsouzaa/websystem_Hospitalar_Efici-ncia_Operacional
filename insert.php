<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = buildInsertSQL($TABLE, $COLUMNS);
  $stmt = $pdo->prepare($sql);
  $bind = [];
  foreach ($COLUMNS as $c) {
    $bind[':' . $c['name']] = $_POST[$c['name']] ?? null;
  }
  try {
    $stmt->execute($bind);
    header('Location: index.php');
    exit;
  } catch (Throwable $e) {
    $errors[] = $e->getMessage();
  }
}
?><!doctype html><html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Novo</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

  <h1>Novo registro — <?php echo h($TABLE); ?></h1>
  <a class="button" href="index.php">Voltar</a>

  <?php
  
  if ($errors) {
    echo "<div class='danger'>" . h(implode(' | ', $errors)) . "</div>";
  }
  ?>

  <form method="post">
    <?php foreach ($COLUMNS as $c) { ?>
      <label><?php echo h($c['label']); ?></label>
      <input type="<?php echo h($c['type'] ?? 'text'); ?>" name="<?php echo h($c['name']); ?>" step="<?php echo h($c['step'] ?? ''); ?>" required>
    <?php } ?>
    <input type="submit" value="Salvar">
  </form>

</div>

</body>
</html>
