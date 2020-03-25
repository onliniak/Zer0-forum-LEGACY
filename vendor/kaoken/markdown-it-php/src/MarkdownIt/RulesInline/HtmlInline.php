<?php
// Process html tags
namespace Kaoken\MarkdownIt\RulesInline;
use Kaoken\MarkdownIt\Common\Utils;

use Kaoken\MarkdownIt\Common\HtmlRegexs;

class HtmlInline
{
    protected function isLetter($c)
    {
        $ch = ord($c);
        /*eslint no-bitwise:0*/
        $lc = $ch | 0x20; // to lower case
        return ($lc >= 0x61/* a */) && ($lc <= 0x7a/* z */);
    }
    /**
     * @param StateInline $state
     * @param boolean     $silent
     * @return bool
     */
    public function htmlInline(&$state, $silent=false)
    {
        $pos = $state->pos;

        if (!$state->md->options->html) { return false; }

        // Check start
        $max = $state->posMax;
        if ($state->src[$pos] !== '<' || $pos + 2 >= $max) {
            return false;
        }

        // Quick fail on second char
        $ch = $state->src[$pos + 1];
        if ($ch !== '!' &&
            $ch !== '?' &&
            $ch !== '/' &&
            !self::isLetter($ch)) {
            return false;
        }

        if ( !preg_match(HtmlRegexs::HTML_TAG_RE, substr($state->src, $pos), $match )) { return false; }

        if (!$silent) {
            $token         = $state->push('html_inline', '', 0);
            $token->content = substr($state->src, $pos, ($pos + strlen($match[0]))-$pos);
        }
        $state->pos += strlen($match[0]);
        return true;
    }
}