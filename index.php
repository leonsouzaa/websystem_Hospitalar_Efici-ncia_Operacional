<?php
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/functions.php';

$search = $_GET['q'] ?? '';
$where = '';
$params = [];

if ($search !== '') {
  $like = [];
  foreach ($COLUMNS as $c) {
    $like[] = $c['name'] . " LIKE :q";
  }
  $where = "WHERE " . implode(" OR ", $like);
  $params[':q'] = "%{$search}%";
}

$sql = "SELECT id," . implode(',', array_map(fn ($c) => $c['name'], $COLUMNS)) . " FROM {$TABLE} {$where} ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

?><!doctype html><html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>CRUD PDO</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

  <h1>CRUD Base (PDO) — <?php echo h($TABLE); ?></h1>

  <form method="get">
    <input name="q" placeholder="Pesquisar..." value="<?php echo h($search); ?>">
    <input type="submit" value="Filtrar">
    <a class="button" href="index.php">Limpar</a>
    <a class="button" href="insert.php">+ Novo</a>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <?php foreach ($COLUMNS as $c) {
          echo "<th>" . h($c['label']) . "</th>";
        } ?>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (empty($rows)) {
        echo "<tr><td colspan='" . (count($COLUMNS) + 2) . "'><i>Nenhum registro.</i></td></tr>";
      } else {
        foreach ($rows as $r) {
          echo "<tr>";
          echo "<td>" . h($r['id']) . "</td>";
          foreach ($COLUMNS as $c) {
            echo "<td>" . h($r[$c['name']]) . "</td>";
          }
          echo "<td><a class='button' href='update.php?id=" . h($r['id']) . "'>Editar</a> <a class='button danger' href='delete.php?id=" . h($r['id']) . "' onclick='return confirm(\"Excluir?\")'>Excluir</a></td>";
          echo "</tr>";
        }
      }
      ?>
    </tbody>
  </table>

</div>
</body>
</html>
