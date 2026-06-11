<?php
session_start();
require_once '../includes/DatabaseConnection.php';

// check user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// check user is admin
if (empty($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header('Location: ../home.php');
    exit;
}

// get form values
$title = trim($_POST['Title'] ?? '');
$authorsRaw = trim($_POST['Authors'] ?? '');
$abstract = trim($_POST['Abstract'] ?? '');
$yearRaw = trim($_POST['Year'] ?? '');
$venue = trim($_POST['Venue'] ?? '');
$publisher = trim($_POST['Publisher'] ?? '');
$doi = trim($_POST['DOI'] ?? '');
$url = trim($_POST['URL'] ?? '');
$pdfUrl = trim($_POST['PDFUrl'] ?? '');

// keep old form values
$_SESSION['old_source_form'] = [
    'Title' => $title,
    'Authors' => $authorsRaw,
    'Abstract' => $abstract,
    'Year' => $yearRaw,
    'Venue' => $venue,
    'Publisher' => $publisher,
    'DOI' => $doi,
    'URL' => $url,
    'PDFUrl' => $pdfUrl
];

// validation
if ($title === '') {
    $_SESSION['form_error'] = 'Title is required.';
    header('Location: sources-admin.php');
    exit;
}

if ($url === '' && $pdfUrl === '') {
    $_SESSION['form_error'] = 'Please provide at least a Website URL or a PDF URL.';
    header('Location: sources-admin.php');
    exit;
}

// validate year
$year = null;
if ($yearRaw !== '') {
    if (!ctype_digit($yearRaw)) {
        $_SESSION['form_error'] = 'Year must be a valid number.';
        header('Location: sources-admin.php');
        exit;
    }
    $year = (int)$yearRaw;
}

// split authors into an array
function parseAuthors(string $authorsRaw): array {
    $authors = [];

    if ($authorsRaw !== '') {
        $parts = preg_split('/[\r\n,]+/', $authorsRaw);

        foreach ($parts as $part) {
            $name = trim($part);
            if ($name !== '') {
                $authors[] = $name;
            }
        }
    }

    return array_values(array_unique($authors));
}

// generate keywords from abstract
function generateKeywordsFromAbstract(string $abstract, int $limit = 6): array {
    $abstract = mb_strtolower($abstract);
    $abstract = preg_replace('/[^\p{L}\p{N}\s-]+/u', ' ', $abstract);
    $abstract = preg_replace('/\s+/', ' ', $abstract);
    $words = preg_split('/\s+/', trim($abstract));

    if (!$words) {
        return [];
    }

    $stopWords = [
        'the','and','for','are','with','that','this','from','into','their','they','them',
        'have','has','had','was','were','been','being','about','between','through','during',
        'while','where','which','what','when','than','then','also','both','each','such',
        'these','those','into','onto','over','under','within','across','because','including',
        'include','includes','used','using','use','uses','study','research','paper',
        'students','student','university','universities','data','find','finds','found',
        'argue','argues','support','supports','supported','current','currently','multiple',
        'understand','understanding','identify','identifies','identified'
    ];

    $counts = [];

    foreach ($words as $word) {
        $word = trim($word);

        // skip short or empty words
        if ($word === '' || mb_strlen($word) < 4) {
            continue;
        }

        // skip common stop words
        if (in_array($word, $stopWords, true)) {
            continue;
        }

        // count keyword frequency
        if (!isset($counts[$word])) {
            $counts[$word] = 0;
        }

        $counts[$word]++;
    }

    arsort($counts);

    $keywords = array_slice(array_keys($counts), 0, $limit);

    return array_values(array_unique($keywords));
}

try {
    // start transaction
    $pdo->beginTransaction();

    // insert source
    $stmt = $pdo->prepare("
        INSERT INTO source (Title, Abstract, Year, Venue, Publisher, DOI, URL, PDFUrl)
        VALUES (:title, :abstract, :year, :venue, :publisher, :doi, :url, :pdfurl)
    ");

    $stmt->execute([
        ':title' => $title,
        ':abstract' => $abstract !== '' ? $abstract : null,
        ':year' => $year,
        ':venue' => $venue !== '' ? $venue : null,
        ':publisher' => $publisher !== '' ? $publisher : null,
        ':doi' => $doi !== '' ? $doi : null,
        ':url' => $url !== '' ? $url : null,
        ':pdfurl' => $pdfUrl !== '' ? $pdfUrl : null
    ]);

    $sourceId = (int)$pdo->lastInsertId();

    // insert authors and sourceauthor links
    $authors = parseAuthors($authorsRaw);

    $findAuthor = $pdo->prepare("
        SELECT AuthorID
        FROM author
        WHERE FullName = :name
        LIMIT 1
    ");

    $insertAuthor = $pdo->prepare("
        INSERT INTO author (FullName)
        VALUES (:name)
    ");

    $insertSourceAuthor = $pdo->prepare("
        INSERT INTO sourceauthor (SourceID, AuthorID, AuthorOrder)
        VALUES (:sourceid, :authorid, :authororder)
    ");

    $authorOrder = 1;

    foreach ($authors as $authorName) {
        $findAuthor->execute([':name' => $authorName]);
        $authorId = $findAuthor->fetchColumn();

        // insert author if not found
        if (!$authorId) {
            $insertAuthor->execute([':name' => $authorName]);
            $authorId = (int)$pdo->lastInsertId();
        } else {
            $authorId = (int)$authorId;
        }

        // link source to author
        $insertSourceAuthor->execute([
            ':sourceid' => $sourceId,
            ':authorid' => $authorId,
            ':authororder' => $authorOrder
        ]);

        $authorOrder++;
    }

    // generate keywords from abstract
    $generatedKeywords = [];
    if ($abstract !== '') {
        $generatedKeywords = generateKeywordsFromAbstract($abstract, 6);
    }

    $findKeyword = $pdo->prepare("
        SELECT KeywordID
        FROM keyword
        WHERE Word = :word
        LIMIT 1
    ");

    $insertKeyword = $pdo->prepare("
        INSERT INTO keyword (Word)
        VALUES (:word)
    ");

    $checkSourceKeyword = $pdo->prepare("
        SELECT COUNT(*)
        FROM sourcekeyword
        WHERE SourceID = :sourceid AND KeywordID = :keywordid
    ");

    $insertSourceKeyword = $pdo->prepare("
        INSERT INTO sourcekeyword (SourceID, KeywordID)
        VALUES (:sourceid, :keywordid)
    ");

    foreach ($generatedKeywords as $word) {
        $findKeyword->execute([':word' => $word]);
        $keywordId = $findKeyword->fetchColumn();

        // insert keyword if not found
        if (!$keywordId) {
            $insertKeyword->execute([':word' => $word]);
            $keywordId = (int)$pdo->lastInsertId();
        } else {
            $keywordId = (int)$keywordId;
        }

        // check if source-keyword link exists
        $checkSourceKeyword->execute([
            ':sourceid' => $sourceId,
            ':keywordid' => $keywordId
        ]);

        // insert source-keyword link if missing
        if ((int)$checkSourceKeyword->fetchColumn() === 0) {
            $insertSourceKeyword->execute([
                ':sourceid' => $sourceId,
                ':keywordid' => $keywordId
            ]);
        }
    }

    $pdo->commit();

    unset($_SESSION['old_source_form']);
    $_SESSION['form_success'] = 'Source added successfully with authors and generated keywords.';
} catch (PDOException $e) {
    // roll back transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['form_error'] = 'Could not add source: ' . $e->getMessage();
}

header('Location: sources-admin.php');
exit;