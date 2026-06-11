<?php

// Source Queries
function getSourceSummary(PDO $pdo, int $sourceId): ?array {
    $sql = "
    SELECT 
      s.SourceID, s.Title, s.Abstract, s.Year, s.Venue,
      GROUP_CONCAT(DISTINCT a.FullName ORDER BY sa.AuthorOrder SEPARATOR ', ') AS Authors,
      GROUP_CONCAT(DISTINCT k.Word ORDER BY k.Word SEPARATOR ', ') AS Keywords
    FROM source s
    LEFT JOIN sourceauthor sa ON sa.SourceID = s.SourceID
    LEFT JOIN author a ON a.AuthorID = sa.AuthorID
    LEFT JOIN sourcekeyword sk ON sk.SourceID = s.SourceID
    LEFT JOIN keyword k ON k.KeywordID = sk.KeywordID
    WHERE s.SourceID = :id
    GROUP BY s.SourceID
    LIMIT 1;
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $sourceId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function getFlashcardsForSource(PDO $pdo, int $sourceId): array {
    $stmt = $pdo->prepare("
        SELECT FlashcardID, Front, Back, GeneratedBy, CreatedAt
        FROM flashcard
        WHERE SourceID = :id
        ORDER BY FlashcardID ASC
    ");
    $stmt->execute([':id' => $sourceId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Helper Functions

function uniqueBullets(array $points): array {
    $out = [];
    $seen = [];

    foreach ($points as $p) {
        $p = trim($p);
        if ($p === '') {
            continue;
        }

        $key = mb_strtolower($p);
        if (isset($seen[$key])) {
            continue;
        }

        $seen[$key] = true;
        $out[] = $p;
    }

    return $out;
}

function bullets(array $points): string {
    $points = uniqueBullets($points);

    if (empty($points)) {
        return '• No clear information available from this source.';
    }

    $clean = [];
    foreach ($points as $p) {
        $clean[] = '• ' . $p;
    }

    return implode("\n", $clean);
}

function cleanText(string $text): string {
    $text = trim(preg_replace('/\s+/', ' ', $text));
    return rtrim($text, ".;: ");
}

// Abstract Fact Extraction

function extractFactsFromAbstract(string $abstract): array {
    $abstract = trim(preg_replace('/\s+/', ' ', $abstract));

    $facts = [
        'topic' => [],
        'benefits' => [],
        'implications' => [],
    ];

    if ($abstract === '') {
        return $facts;
    }

    // Topic / What the Source Examines
    if (preg_match('/\b(examines|investigates|explores|evaluates|assesses|tests|aims to understand|seeks to understand|looks at|focuses on|aims to evaluate)\b\s+(.*?)(?:[.;]|$)/i', $abstract, $m)) {
        $phrase = cleanText($m[2]);
        $phrase = preg_replace('/^how\s+/i', '', $phrase);
        if ($phrase !== '') {
            $facts['topic'][] = $phrase;
        }
    }

    if (empty($facts['topic']) && preg_match('/\bthis (study|research|paper)\s+(.*?)(?:[.;]|$)/i', $abstract, $m)) {
        $phrase = cleanText($m[2]);
        if ($phrase !== '') {
            $facts['topic'][] = $phrase;
        }
    }

    // Benefits / Findings
    if (preg_match_all('/\b(improves|increases|reduces|decreases|enhances|boosts|supports|leads to|results in|shows|demonstrates|suggests|finds|found|reveals|highlights)\b\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = cleanText($m[1] . ' ' . $m[2]);
            if ($phrase !== '') {
                $facts['benefits'][] = $phrase;
            }
        }
    }

    if (preg_match('/\bwe find that\b\s+([^.;]+)/i', $abstract, $m)) {
        $phrase = cleanText($m[1]);
        if ($phrase !== '') {
            $facts['benefits'][] = $phrase;
        }
    }

    if (preg_match('/\bthe analysis reveals that\b\s+([^.;]+)/i', $abstract, $m)) {
        $phrase = cleanText($m[1]);
        if ($phrase !== '') {
            $facts['benefits'][] = $phrase;
        }
    }

    // Implications

    if (preg_match_all('/\bimplications?\s+for\b\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = cleanText($m[1]);
            if ($phrase !== '') {
                $facts['implications'][] = $phrase;
            }
        }
    }

    if (preg_match_all('/\bthis has implications for\b\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = cleanText($m[1]);
            if ($phrase !== '') {
                $facts['implications'][] = $phrase;
            }
        }
    }

    if (preg_match_all('/\b(challenges?|considerations?|issues?|limitations?|problems?)\b\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = cleanText($m[1] . ' ' . $m[2]);
            if ($phrase !== '') {
                $facts['implications'][] = $phrase;
            }
        }
    }

    if (preg_match_all('/\b(areas? for potential improvement|areas? for improvement|potential improvement)\b/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = cleanText($m[1]);
            if ($phrase !== '') {
                $facts['implications'][] = $phrase;
            }
        }
    }

    if (preg_match_all('/\brequire[s]?\s+updates?\s+to\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = 'require updates to ' . cleanText($m[1]);
            $facts['implications'][] = $phrase;
        }
    }

    if (preg_match_all('/\bcannot be effectively addressed\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = 'cannot be effectively addressed ' . cleanText($m[1]);
            $facts['implications'][] = $phrase;
        }
    }

    if (preg_match_all('/\bhighlights?\b\s+([^.;]+)/i', $abstract, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $m) {
            $phrase = cleanText($m[1]);
            if ($phrase !== '') {
                $facts['implications'][] = $phrase;
            }
        }
    }

    foreach ($facts as $key => $values) {
        $facts[$key] = uniqueBullets($values);
    }

    return $facts;
}

// Flashcard Generation

function generateFlashcardsForSource(PDO $pdo, int $sourceId): int {
    $source = getSourceSummary($pdo, $sourceId);
    if (!$source) {
        return 0;
    }

    $abstract = trim($source['Abstract'] ?? '');
    $keywordsRaw = trim($source['Keywords'] ?? '');
    $facts = extractFactsFromAbstract($abstract);

    // Q1: Keywords
    $keywords = [];
    if ($keywordsRaw !== '') {
        $keywords = array_values(array_filter(array_map('trim', explode(',', $keywordsRaw))));
    }

    $keywordsAnswer = !empty($keywords)
        ? bullets(array_slice($keywords, 0, 10))
        : bullets(['No keywords saved for this source.']);

    // Q2: Implications
    $implicationsAnswer = !empty($facts['implications'])
        ? bullets(array_slice($facts['implications'], 0, 5))
        : bullets(['No clear implications were identified from the abstract.']);

    // Q3: Benefits / Findings
    $benefitsAnswer = !empty($facts['benefits'])
        ? bullets(array_slice($facts['benefits'], 0, 5))
        : bullets(['No clear benefits or findings were identified from the abstract.']);

    // Q4: Source Topic
    if (!empty($facts['topic'])) {
        $topicAnswer = bullets(array_slice($facts['topic'], 0, 3));
    } elseif ($abstract !== '') {
        $topicAnswer = bullets([mb_strimwidth(cleanText($abstract), 0, 220, '...')]);
    } else {
        $topicAnswer = bullets(['No abstract is available for this source.']);
    }

    $cards = [
        [
            'Front' => 'Keywords from this source:',
            'Back'  => $keywordsAnswer
        ],
        [
            'Front' => 'What implications are mentioned?',
            'Back'  => $implicationsAnswer
        ],
        [
            'Front' => 'What benefits or findings are mentioned?',
            'Back'  => $benefitsAnswer
        ],
        [
            'Front' => 'What does the source examine?',
            'Back'  => $topicAnswer
        ]
    ];

    // check for existing flashcards
    $exists = $pdo->prepare("
        SELECT COUNT(*)
        FROM flashcard
        WHERE SourceID = :sid AND Front = :f AND Back = :b
    ");

    // insert new flashcards
    $ins = $pdo->prepare("
        INSERT INTO flashcard (SourceID, Front, Back, GeneratedBy)
        VALUES (:sid, :f, :b, 'RULES')
    ");

    $count = 0;

    foreach ($cards as $c) {
        $front = trim($c['Front']);
        $back = trim($c['Back']);

        if ($front === '' || $back === '') {
            continue;
        }

        $exists->execute([
            ':sid' => $sourceId,
            ':f'   => $front,
            ':b'   => $back
        ]);

        if ((int)$exists->fetchColumn() > 0) {
            continue;
        }

        $ins->execute([
            ':sid' => $sourceId,
            ':f'   => $front,
            ':b'   => $back
        ]);

        $count++;
    }

    return $count;
}