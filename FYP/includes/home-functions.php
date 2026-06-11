<?php
// Check Table Exists 
function tableExists(PDO $pdo, string $table): bool
{
    $sql = "SELECT COUNT(*) 
            FROM information_schema.tables 
            WHERE table_schema = DATABASE() AND table_name = :t";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':t' => $table]);
    return (int)$stmt->fetchColumn() > 0;
}

// Get Row Count
function getCount(PDO $pdo, string $table): int
{
    if (!tableExists($pdo, $table)) return 0;
    $stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
    return (int)$stmt->fetchColumn();
}

// Get Recent Records
function getRecent(PDO $pdo, string $table, string $pk, array $fields, int $limit = 5): array
{
    if (!tableExists($pdo, $table)) return [];

    $select = implode(', ', array_map(fn($f) => "`$f`", $fields));
    $sql = "SELECT $select FROM `$table` ORDER BY `$pk` DESC LIMIT :lim";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}