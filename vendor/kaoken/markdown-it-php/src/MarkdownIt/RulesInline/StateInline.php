<?php
// Inline parser state

namespace Kaoken\MarkdownIt\RulesInline;

use Exception;
use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Token;
use Kaoken\MarkdownIt\Common\ArrayObj;
use stdClass;

class StateInline
{
	public $src = '';
    /**
     * @var object
     */
	public $env;
    /**
     * @var MarkdownIt
     */
	public $md = null;
    /**
     * @var Token[]
     */
	public $tokens = [];

    /**
     * @var array
     */
    public $tokens_meta = [];

    /**
     * @var int
     */
	public $pos         = 0;
    /**
     * @var int
     */
	public $posMax      = -1;
    /**
     * @var int
     */
	public $level       = 0;
    /**
     * @var string
     */
	public $pending     = '';
    /**
     * @var int
     */
	public $pendingLevel= 0;

    /**
     * Stores { start: end } pairs. Useful for backtrack
     * optimization of pairs parse (emphasis, strikes).
     * @var array
     */
	public $cache = [];

    /**
     * @var ArrayObj List of emphasis-like delimiters for current tag
     */
	public $delimiters = null;

    /**
     * @var ArrayObj Stack of delimiter lists for upper level tags
     */
    private $_prev_delimiters = null;

    /**
     * @param string $src
     * @param MarkdownIt $md
     * @param object $env
     * @param Token[]  $outTokens
     */
    public function __construct($src, $md, $env, &$outTokens)
    {
        $this->cache            = [];
        $this->src              = $src;
        $this->posMax           = strlen ($src);
        $this->md               = $md;
        $this->env              = $env;
        $this->tokens           = &$outTokens;
        $this->tokens_meta      = new ArrayObj(count($outTokens));
        $this->delimiters       = new ArrayObj();
        $this->_prev_delimiters = new ArrayObj();
    }

    /**
     * @return Token
     */
    public function pushPending()
    {
        $token          = new Token('text', '', 0);
        $token->content = $this->pending;
        $token->level   = $this->pendingLevel;
        $this->tokens[] = $token;
        $this->pending  = '';
        return $token;
    }

    /**
     * @param string  $type
     * @param string  $tag
     * @param integer $nesting
     * @return Token
     */
    public function createToken($type, $tag, $nesting)
    {
        return new Token($type, $tag, $nesting);
    }

    /**
     * Push new token to "stream".
     * If pending text exists - flush it as text token
     * @param string  $type
     * @param string  $tag
     * @param integer $nesting
     * @return Token
     */
    public function push($type, $tag, $nesting)
    {
        if ($this->pending) {
            $this->pushPending();
        }

        $token      = $this->createToken($type, $tag, $nesting);
        $token_meta = null;

        if ($nesting < 0) {
            // closing tag
            $this->level--;
            $this->delimiters = $this->_prev_delimiters->pop() ?? new ArrayObj();
        }

        $token->level = $this->level;
        if ($nesting > 0) {
            // opening tag
            $this->level++;
            $this->_prev_delimiters->push($this->delimiters);
            $this->delimiters           = new ArrayObj();
            $token_meta                 = (object)[
                "delimiters"  =>  $this->delimiters
            ];
        }
        $this->pendingLevel     = $this->level;
        $this->tokens[]         = $token;
        $this->tokens_meta[]    = $token_meta;
        return $token;
    }


    /**
     * Scan a sequence of emphasis-like markers, and determine whether
     * it can start an emphasis sequence or end an emphasis sequence.
     * @param integer $start         position to scan from (it should point at a valid marker);
     * @param boolean $canSplitWord  determine if these markers can be found inside a word
     * @return stdClass
     * @throws Exception
     */
    public function scanDelims($start, $canSplitWord)
    {
        $pos            = $start;
        $left_flanking  = true;
        $right_flanking = true;
        $can_open       = false;
        $can_close      = false;
        $max            = $this->posMax;
        $marker         = $this->md->utils->currentCharUTF8($this->src, $start, $outLen);


        // treat beginning of the line as a whitespace
//        $lastChar = $start > 0 ? $this->src[$start-1] : ' ';
        $lastPos = $start;
        if( $start > 0 ){
            $lastChar = $this->md->utils->lastCharUTF8($this->src, $start, $lastPos);
            if( $lastChar === '' ){
                throw new Exception('scanDelims(), last char unexpected error...');
            }
        }
        else
            $lastChar = ' ';

        //while ($pos < $max && $this->src[$pos] === $marker) { $pos++; }
        while ($pos < $max && ($nextChar=$this->md->utils->currentCharUTF8($this->src, $pos, $outLen)) === $marker) {
            $pos+=$outLen;
        }
        $count = $pos - $start;

        // treat end of the line as a whitespace
        $nextChar = $pos < $max ? $nextChar : ' ';

        $isLastPunctChar = $this->md->utils->isMdAsciiPunct($lastChar) || $this->md->utils->isPunctChar($lastChar);
        $isNextPunctChar = $this->md->utils->isMdAsciiPunct($nextChar) || $this->md->utils->isPunctChar($nextChar);

        $isLastWhiteSpace = $this->md->utils->isWhiteSpace($lastChar);
        $isNextWhiteSpace = $this->md->utils->isWhiteSpace($nextChar);

        if ($isNextWhiteSpace) {
            $left_flanking = false;
        } else if ($isNextPunctChar) {
            if (!($isLastWhiteSpace || $isLastPunctChar)) {
                $left_flanking = false;
            }
        }

        if ($isLastWhiteSpace) {
            $right_flanking = false;
        } else if ($isLastPunctChar) {
            if (!($isNextWhiteSpace || $isNextPunctChar)) {
                $right_flanking = false;
            }
        }

        if (!$canSplitWord) {
            $can_open  = $left_flanking  && (!$right_flanking || $isLastPunctChar);
            $can_close = $right_flanking && (!$left_flanking  || $isNextPunctChar);
        } else {
            $can_open  = $left_flanking;
            $can_close = $right_flanking;
        }
        $obj = new stdClass();
        $obj->can_open      = $can_open;
        $obj->can_close     = $can_close;
        $obj->length        = $count;

        return $obj;
    }
}