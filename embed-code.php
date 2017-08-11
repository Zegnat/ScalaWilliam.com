<?php

define('PRISM_GRAMMARS', '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/components/prism-%s.min.js');

$stylesheets = [
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/themes/prism.min.css',
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/themes/prism-okaidia.min.css',
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/plugins/command-line/prism-command-line.min.css',
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/plugins/toolbar/prism-toolbar.min.css',
];
$scripts = [
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/prism.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/plugins/command-line/prism-command-line.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/plugins/toolbar/prism-toolbar.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.13/clipboard.min.js',
    '//cdnjs.cloudflare.com/ajax/libs/prism/1.6.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js',
];
$dependents = [
    'scala' => ['java' => true],
];

function process_file($filename) {
    global $stylesheets, $scripts, $dependents;
    $usedLanguages = [];
    libxml_use_internal_errors(true);
    $dom = new \DOMDocument();
    $dom->loadHTMLFile($filename);
    $xpath = new \DOMXpath($dom);
    $elements = $xpath->query('//pre[@data-code-include]');
    foreach ($elements as $element) {
        $codefile = pathinfo($filename, PATHINFO_DIRNAME)
            . DIRECTORY_SEPARATOR
            . $element->getAttribute('data-code-include');
        $language = '';
        if ($element->hasAttribute('data-code-language')) {
            $language = $element->getAttribute('data-code-language');
        } elseif ($element->hasAttribute('class')) {
            $classes = explode(' ', $element->getAttribute('class'));
            foreach ($classes as $class) {
                if (strpos($class, 'language-') === 0) {
                    $language = substr($class, 9);
                    break;
                }
            }
        }
        if ($language === '') {
            $extension = pathinfo($codefile, PATHINFO_EXTENSION);
            $extensionsMap = [
                'py' => 'python',
                'sh' => 'bash',
                'js' => 'javascript',
            ];
            if (array_key_exists($extension, $extensionsMap)) {
                $language = $extensionsMap[$extension];
            } else {
                $language = $extension;
            }
        }
        if (array_key_exists($language, $dependents)) {
            $usedLanguages = array_merge($usedLanguages, $dependents[$language]);
        }
        $usedLanguages[$language] = true;
        $code = $dom->createElement('code');
        $code->setAttribute('class', 'language-' . $language);
        $code->appendChild($dom->createTextNode(rtrim(file_get_contents($codefile),"\n\r")));
        $element->appendChild($code);
    }
    if (count($usedLanguages) > 0) {
        // We have inserted code, so lets load all the libraries.
        $head = $dom->getElementsByTagName('head')->item(0);
        $body = $dom->getElementsByTagName('body')->item(0);
        $style = $head->getElementsByTagName('style');
        if ($style->length > 0) {
            $style = $style->item(0);
        } else {
            $style = null;
        }
        foreach ($stylesheets as $stylesheet) {
            $link = $dom->createElement('link');
            $link->setAttribute('rel', 'stylesheet');
            $link->setAttribute('href', $stylesheet);
            $head->insertBefore($link, $style);
        }
        foreach ($scripts as $src) {
            $script = $dom->createElement('script');
            $script->setAttribute('src', $src);
            $body->appendChild($script);
        }
        foreach (array_keys($usedLanguages) as $language) {
            $script = $dom->createElement('script');
            $script->setAttribute('src', sprintf(PRISM_GRAMMARS, $language));
            $body->appendChild($script);
        }
    }
    echo $dom->saveHTML();
}

$failed = false;
foreach(array_slice($argv, 1) as $filename) {
    if (pathinfo($filename, PATHINFO_EXTENSION) === 'html') {
        process_file($filename);
    } else {
        $failed = true;
        error_log("Cannot process file $filename because no .html extension.");
    }
}
if ( $failed ) {
    exit(1);
}
