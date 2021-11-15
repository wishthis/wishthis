<?php

declare(strict_types=1);

namespace Brick\Schema\Interfaces;

use Brick\Schema\SchemaTypeList;

/**
 * http://schema.org/Question
 *
 * @property-read SchemaTypeList<Integer>         $upvoteCount     The number of upvotes this question, answer or comment has received from the community.
 * @property-read SchemaTypeList<Integer>         $answerCount     The number of answers this question has received.
 * @property-read SchemaTypeList<Answer|ItemList> $acceptedAnswer  The answer(s) that has been accepted as best, typically on a Question/Answer site. Sites vary in their selection mechanisms, e.g. drawing on community opinion and/or the view of the Question author.
 * @property-read SchemaTypeList<Answer|ItemList> $suggestedAnswer An answer (possibly one of several, possibly incorrect) to a Question, e.g. on a Question/Answer site.
 * @property-read SchemaTypeList<Integer>         $downvoteCount   The number of downvotes this question, answer or comment has received from the community.
 */
interface Question extends CreativeWork
{
}
