<?php

namespace ConventionalChangelog\Git;

use ConventionalChangelog\Git\Commit\Body;
use ConventionalChangelog\Git\Commit\Footer;
use ConventionalChangelog\Git\Commit\Mention;
use ConventionalChangelog\Git\Commit\Subject;
use ConventionalChangelog\Helper\Formatter;
use ConventionalChangelog\Type\Stringable;

class Commit implements Stringable
{
    /**
     * @var string
     */
    protected const PATTERN_FOOTER = "/(?<token>^([a-z0-9_-]+|BREAKING[[:blank:]]CHANGES?))(?<value>([:][[:blank:]]|[:]?[[:blank:]][#](?=\w)).*?)$/iums";
    /**
     * @var string
     */
    protected const PATTERN_MENTION = "/(?:^|\s+)(?<mention>@(?<user>[a-z\d](?:[a-z\d]|-(?=[a-z\d])){0,38}))(?:$|\s+)/smi";

    /**
     * Raw content.
     *
     * @var string
     */
    public $raw;

    /**
     * Subject content.
     *
     * @var Subject
     */
    public $subject;

    /**
     * Body content.
     *
     * @var Body
     */
    public $body;

    /**
     * Footers.
     *
     * @var Footer[]
     */
    public $footers = [];

    /**
     * User Mentions.
     *
     * @var Mention[]
     */
    public $mentions = [];

    /**
     * Sha hash.
     *
     * @var string
     */
    public $hash;

    /**
     * @var \DateTime
     */
    public $authorDate;

    /**
     * @var string
     */
    public $authorName;

    /**
     * @var string
     */
    public $authorEmail;

    /**
     * @var \DateTime
     */
    public $committerDate;

    /**
     * @var string
     */
    public $committerName;

    /**
     * @var string
     */
    public $committerEmail;

    public function __construct(string $commit = null)
    {
        // New commit or empty commit
        if (empty($commit)) {
            $this->setSubject('')
                 ->setBody('');

            return;
        }

        $raw = Formatter::clean($commit);
        $this->setRaw($raw);
        $this->parse();
    }

    public function __wakeup()
    {
        $this->parse();
    }

    public function __toString(): string
    {
        if (!empty($this->raw)) {
            return $this->raw;
        }

        $header = $this->getSubject();
        $message = $this->getMessage();
        $string = $header . "\n\n" . $message;

        return Formatter::clean($string);
    }

    /**
     * From array.
     *
     * @throws \Exception
     */
    public function fromArray(array $array): self
    {
        if (isset($array['raw'])) {
            $this->setRaw($array['raw']);
        }
        if (isset($array['hash'])) {
            $this->setHash($array['hash']);
        }
        if (isset($array['authorName'])) {
            $this->setAuthorName($array['authorName']);
        }
        if (isset($array['authorEmail'])) {
            $this->setAuthorEmail($array['authorEmail']);
        }
        if (isset($array['authorDate'])) {
            $date = new \DateTime($array['authorDate']);
            $this->setAuthorDate($date);
        }
        if (isset($array['committerName'])) {
            $this->setCommitterName($array['committerName']);
        }
        if (isset($array['committerEmail'])) {
            $this->setCommitterEmail($array['committerEmail']);
        }
        if (isset($array['committerDate'])) {
            $date = new \DateTime($array['committerDate']);
            $this->setCommitterDate($date);
        }
        $this->parse();

        return $this;
    }

    public function setRaw(string $raw): self
    {
        $this->raw = $raw;

        return $this;
    }

    public function setHash(string $hash): self
    {
        if ($this->isValidHash($hash)) {
            $this->hash = $hash;
        }

        return $this;
    }

    public function getRaw(): ?string
    {
        return $this->raw;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function getShortHash(): ?string
    {
        return substr($this->hash, 0, 6);
    }

    public function getAuthorDate(): \DateTime
    {
        return $this->authorDate;
    }

    /**
     * @param \DateTime|\DateTimeImmutable $authorDate
     */
    public function setAuthorDate(\DateTimeInterface $authorDate): Commit
    {
        $this->authorDate = $authorDate;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    public function setAuthorName(string $authorName): Commit
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail(string $authorEmail): Commit
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    public function getCommitterDate(): \DateTime
    {
        return $this->committerDate;
    }

    /**
     * @param \DateTime|\DateTimeImmutable $committerDate
     */
    public function setCommitterDate(\DateTimeInterface $committerDate): Commit
    {
        $this->committerDate = $committerDate;

        return $this;
    }

    public function getCommitterName(): ?string
    {
        return $this->committerName;
    }

    public function setCommitterName(string $committerName): Commit
    {
        $this->committerName = $committerName;

        return $this;
    }

    public function getCommitterEmail(): ?string
    {
        return $this->committerEmail;
    }

    public function setCommitterEmail(string $committerEmail): Commit
    {
        $this->committerEmail = $committerEmail;

        return $this;
    }

    public function getBody(): Body
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $body = Formatter::clean($body);
        $footers = [];
        if (preg_match_all(self::PATTERN_FOOTER, $body, $matches, PREG_SET_ORDER, 0)) {
            foreach ($matches as $match) {
                $footer = $match[0];
                $body = str_replace($footer, '', $body);
                $value = ltrim((string)$match['value'], ':');
                $value = Formatter::clean($value);
                $footers[] = new Footer((string)$match['token'], $value);
            }
        }
        $this->setFooters($footers);

        $body = Formatter::clean($body);
        $this->body = new Body($body);

        return $this;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = new Subject($subject);

        return $this;
    }

    /**
     * @param Footer[] $footers
     */
    public function setFooters(array $footers): self
    {
        $this->footers = $footers;

        return $this;
    }

    /**
     * @return Footer[]
     */
    public function getFooters(): array
    {
        return $this->footers;
    }

    /**
     * Set mentions.
     *
     * @param Mention[] $mentions
     */
    public function setMentions(array $mentions): self
    {
        $this->mentions = array_unique($mentions);

        return $this;
    }

    /**
     * Get mentions.
     *
     * @return Mention[]
     */
    public function getMentions(): array
    {
        return $this->mentions;
    }

    public function getMessage(): string
    {
        $footer = implode("\n", $this->footers);

        return $this->body . "\n\n" . $footer;
    }

    /**
     * Check if is valid SHA-1.
     */
    protected function isValidHash(string $hash): bool
    {
        return (bool)preg_match('/^[0-9a-f]{40}$/i', $hash);
    }

    /**
     * Parse raw commit.
     */
    protected function parse()
    {
        // Empty
        if (empty($this->raw)) {
            return;
        }

        $rows = explode("\n", $this->raw);

        $subject = $rows[0];
        $body = '';
        foreach ($rows as $i => $row) {
            if ($i !== 0) {
                $body .= $row . "\n";
            }
        }

        $mentions = [];
        if (preg_match_all(self::PATTERN_MENTION, $this->raw, $matches)) {
            foreach ($matches['user'] as $match) {
                $mentions[] = new Mention($match);
            }
        }

        $this->setSubject($subject)
             ->setBody($body)
             ->setMentions($mentions);
    }
}
