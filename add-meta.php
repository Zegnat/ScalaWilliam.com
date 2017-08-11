<?php

function process_string($string) {
    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTML($string);
    $xpath = new \DOMXpath($dom);
    $elements = $xpath->query('//head');
    if ($elements->length !== 1) {
        throw new Exception();
    }
    $head = $elements->item(0);

    // Collect data:
    $title = $xpath->query('title', $head)->item(0)->nodeValue;
    $url = $xpath->query('link[@rel="canonical"]', $head)->item(0)->getAttribute('href');
    $description = $xpath->query('meta[@name="description"]', $head)->item(0)->getAttribute('content');
    $avatar = 'https://avatars2.githubusercontent.com/u/2464813';
    $gplusurl = 'https://plus.google.com/u/0/103489630517643950426/';

    // Elements to add:
    $elements = [
        'meta' => [
            ['name' => 'author', 'content' => 'William Narmontas'],
            ['itemprop' => 'name', 'content' => $title],
            ['itemprop' => 'description', 'content' => $description],
            ['itemprop' => 'image', 'content' => $avatar],
            // Twitter
            ['name' => 'twitter:title', 'content' => $title],
            ['name' => 'twitter:card', 'content' => 'summary'],
            ['name' => 'twitter:site', 'content' => '@ScalaWilliam'],
            ['name' => 'twitter:image', 'content' => $avatar],
            ['name' => 'twitter:description', 'content' => $description],
            // Facebook
            ['property' => 'og:title', 'content' => $title],
            ['property' => 'og:url', 'content' => $url],
            ['property' => 'og:site_name', 'content' => 'Scala William'],
            ['property' => 'og:type', 'content' => 'article'],
            ['property' => 'og:description', 'content' => $description],
            ['property' => 'og:image', 'content' => $avatar],
        ],
        'link' => [
            ['rel' => 'author', 'href' => $gplusurl],
            ['rel' => 'publisher', 'href' => $gplusurl],
        ],
    ];

    foreach ($elements as $tagName => $list) {
        foreach ($list as $attributes) {
            // Remove all the old tags, if they exist.
            $attributesSelectors = [];
            foreach ($attributes as $attribute => $value) {
                if (in_array($attribute, ['content', 'href'])) continue;
                $attributesSelectors[] = '@' . $attribute . '=' . quote_xpath($value);
            }
            $find = $xpath->query($tagName . '[' . implode(' and ', $attributesSelectors) . ']', $head);
            foreach ($find as $remove) {
                $remove->parentNode->removeChild($remove);
            }
            // Create the new tag.
            $new = $dom->createElement($tagName);
            foreach ($attributes as $attribute => $value) {
                $new->setAttribute($attribute, $value);
            }
            $head->appendChild($new);
        }
    }

    return $dom->saveHTML();
}

function quote_xpath($value) {
    if (strpos($value, '"') === false) {
        return '"' . $value . '"';
    }
    if (strpos($value, "'") === false) {
        return "'" . $value . "'";
    }
    // Both " and ' are within this string. We need concat.
    // Cf. https://stackoverflow.com/a/45228168
    $parts = explode('"', $value);
    return 'concat("' . implode('", \'"\', "', $parts) . '")';
}

try {
    $input = stream_get_contents(STDIN);
    $out = process_string($input);
    if (!is_string($out)) {
        throw new Exception();
    }
    fwrite(STDOUT, $out);
    exit(0);
} catch (Exception $e) {
    // Something went wrong.
    exit(1);
}
