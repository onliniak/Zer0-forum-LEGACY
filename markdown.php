<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Plugins\MarkdownItEmoji;
use Kaoken\MarkdownIt\Plugins\MarkdownItFootnote;
use Kaoken\MarkdownIt\Plugins\MarkdownItAbbr;

$md = (new MarkdownIt([
  "breaks"=>       true,
  "linkify"=>      true,
]))
->plugin(new MarkdownItEmoji())
->plugin(new MarkdownItFootnote())
->plugin(new MarkdownItAbbr());
