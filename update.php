<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  header('Location:index.php');
  exit;
}

$cols = implode(',', array_map(fn ($c) => $c['name'], $COLUMNS));
$stmt = $pdo->prepare("SELECT id,{$cols} FROM {$TABLE} WHERE id=:id");
$stmt->execute([':id' => $id]);
$cur = $stmt->fetch();

if (!$cur) {
  header('Location:index.php');
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = buildUpdateSQL($TABLE, $COLUMNS);
  $stmt = $pdo->prepare($sql);
  $bind = [':id' => $id];
  foreach ($COLUMNS as $c) {
    $bind[':' . $c['name']] = $_POST[$c['name']] ?? null;
  }
  try {
    $stmt->execute($bind);
    header('Location:index.php');
    exit;
  } catch (Throwable $e) {
    $errors[] = $e->getMessage();
  }
}
?><!doctype html><html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale-1">
  <title>Editar</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

  <h1>Editar #<?php echo h($id); ?> — <?php echo h($TABLE); ?></h1>
  <a class="button" href="index.php">Voltar</a>

  <?php if ($errors) {
    echo "<div class='danger'>" . h(implode(' | ', $errors)) . "</div>";
  } ?>

  <form method="post">
    <?php foreach ($COLUMNS as $c) {
      $name = $c['name'];
      $val = $cur[$name] ?? ''; ?>
      <label><?php echo h($c['label']); ?></label>
      <input type="<?php echo h($c['type'] ?? 'text'); ?>" name="<?php echo h($name); ?>" step="<?php echo h($c['step'] ?? ''); ?>" value="<?php echo h($val); ?>" required>
    <?php } ?>
    <input type="submit" value="Salvar">
  </form>

</div>
</body>
</html>
