<?php
/**
 * Copyright (c) 2014 Vitaly Puzrin.
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 */
/**
 * Copyright (c) 2016 kaoken
 *
 * This software is released under the MIT License.
 * http://opensource.org/licenses/mit-license.php
 *
 *
 * use javascript version 2.0.0
 * @see https://github.com/markdown-$g->group/markdown-$g->group-container/tree/2.0.0/test
 */
namespace Kaoken\Test\MarkdownIt\Plugins\Container;

use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\Plugins\MarkdownItContainer;
use Kaoken\Test\MarkdownItTestgen\MarkdownItTestgen;

Trait ContainerTrait
{
    private function container()
    {
        $this->group('markdown-it-container',function ($g){
            $g->group('api', function ($gg){
                $this->containerApi($gg);
            });
            $g->group('default container', function ($gg){
                $this->containerDefault($gg);
            });
            $g->group('coverage', function ($gg){
                $this->containerMisc($gg);
            });
        });
    }

    private function containerApi($g)
    {
        $res = (new MarkdownIt())
        ->plugin(new MarkdownItContainer(), 'spoiler', [
            'render' => function ($tokens, $idx) {
                return $tokens[$idx]->nesting === 1
                    ? "<details><summary>click me</summary>\n"
                    : "</details>\n";
            }
        ])
        ->render("::: spoiler\n*content*\n:::\n");

        $g->equal($res, "<details><summary>click me</summary>\n<p><em>content</em></p>\n</details>\n", "renderer");

        //------------------------------------------------------------
        $res = (new MarkdownIt())
            ->plugin(new MarkdownItContainer(), 'spoiler', ['marker'=> '->'])
            ->render("->->-> spoiler\n*content*\n->->->\n");

        $g->equal($res, "<div class=\"spoiler\">\n<p><em>content</em></p>\n</div>\n", '2 char marker');


        //------------------------------------------------------------
        $res = (new MarkdownIt())
            ->plugin(new MarkdownItContainer(), 'spoiler', ['marker'=> '`'])
            ->render("``` spoiler\n*content*\n```\n");

        $g->equal($res, "<div class=\"spoiler\">\n<p><em>content</em></p>\n</div>\n",
                'marker should not collide with fence');

        //------------------------------------------------------------
        $res = (new MarkdownIt())
            ->plugin(new MarkdownItContainer(), 'spoiler', ['marker'=> '`'])
            ->render("\n``` not spoiler\n*content*\n```\n");

        $g->equal($res, "<pre><code class=\"language-not\">*content*\n</code></pre>\n",'marker should not collide with fence #2');

        //------------------------------------------------------------
        $g->group('validator', function ($gg) {
            $res = (new MarkdownIt())
                ->plugin(new MarkdownItContainer(), 'name', ['validate' => function () { return false; }])
                ->render(":::foo\nbar\n:::\n");

            $gg->equal($res, "<p>:::foo\nbar\n:::</p>\n",'should skip rule if return value is falsy');

            //------------------------------------------------------------
            $res = (new MarkdownIt())
                ->plugin(new MarkdownItContainer(), 'name', ['validate' => function () { return true; }])
                ->render(":::foo\nbar\n:::\n");

            $gg->equal($res, "<div class=\"name\">\n<p>bar</p>\n</div>\n",'should accept rule if return value is true');

            //------------------------------------------------------------
            /**
             * The javascript version of 'markdown-it v8' also looks like the following.
             *
             *  $count = 6
             */
            $count = 0;

            (new MarkdownIt())
                ->plugin(new MarkdownItContainer(), 'name', ['validate' => function () use(&$count) {
                    $count++;
                }])
                ->parse(":\n::\n:::\n::::\n:::::\n", new \stdClass());

            $gg->equal($count, 3, 'rule should call it');
            // markdown-it version ^8.00
            //$ggg->equal($count, 6);

            //------------------------------------------------------------
            (new MarkdownIt())
                ->plugin(new MarkdownItContainer(), 'name',
                    ['validate' => function ($p) use($gg) { $gg->equal($p, " \tname ",'should not trim params'); return 1; }])
                ->parse("::: \tname \ncontent\n:::\n", new \stdClass());
        });
    }

    private function containerDefault($g)
    {
        $md = new MarkdownIt();
        $md->plugin(new MarkdownItContainer(), 'name');
        $test = new MarkdownItTestgen();

        $test->generate(__DIR__.'/Fixtures/default.txt', [ "header" => true, "assert" => $g ], $md);
    }

    private function containerMisc($g)
    {
        $g->group('marker coverage', function ($gg) {
            $tok = (new MarkdownIt())
                ->plugin(new MarkdownItContainer(), 'fox',
                    [
                        'marker' => 'foo',
                        'validate' => function ($p) use($gg) { $gg->equal($p, 'fox'); return 1; }
                    ]
                )
                ->parse("foofoofoofox\ncontent\nfoofoofoofoo\n");
            $gg->equal($tok[0]->markup, 'foofoofoo');
            $gg->equal($tok[0]->info, 'fox');
            $gg->equal($tok[4]->markup, 'foofoofoofoo');
        });
    }

}