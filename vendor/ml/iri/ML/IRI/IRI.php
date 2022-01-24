<?php

/*
 * (c) Markus Lanthaler <mail@markus-lanthaler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ML\IRI;

/**
 * IRI represents an IRI as per RFC3987.
 *
 * @author Markus Lanthaler <mail@markus-lanthaler.com>
 *
 * @link http://tools.ietf.org/html/rfc3987 RFC3987
 */
class IRI
{
    /**
     * The scheme
     *
     * @var string|null
     */
    private $scheme = null;

    /**
     * The user information
     *
     * @var string|null
     */
    private $userinfo = null;

    /**
     * The host
     *
     * @var string|null
     */
    private $host = null;

    /**
     * The port
     *
     * @var string|null
     */
    private $port = null;

    /**
     * The path
     *
     * @var string
     */
    private $path = '';

    /**
     * The query component
     *
     * @var string|null
     */
    private $query = null;

    /**
     * The fragment identifier
     *
     * @var string|null
     */
    private $fragment = null;


    /**
     * Constructor
     *
     * @param null|string|IRI $iri The IRI.
     *
     * @throws \InvalidArgumentException If an invalid IRI is passed.
     *
     * @api
     */
    public function __construct($iri = null)
    {
        if (null === $iri) {
            return;
        } elseif (is_string($iri)) {
            $this->parse($iri);
        } elseif ($iri instanceof IRI) {
            $this->scheme = $iri->scheme;
            $this->userinfo = $iri->userinfo;
            $this->host = $iri->host;
            $this->port = $iri->port;
            $this->path = $iri->path;
            $this->query = $iri->query;
            $this->fragment = $iri->fragment;
        } else {
            throw new \InvalidArgumentException(
                'Expecting a string or an IRI, got ' .
                (is_object($iri) ? get_class($iri) : gettype($iri))
            );
        }
    }

    /**
     * Get the scheme
     *
     * @return string|null Returns the scheme or null if not set.
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Get the authority
     *
     * @return string|null Returns the authority or null if not set.
     */
    public function getAuthority()
    {
        $authority = null;

        if (null !== $this->host) {

            if (null !== $this->userinfo) {
                $authority .= $this->userinfo . '@';
            }
            $authority .= $this->host;
            if (null !== $this->port) {
                $authority .= ':' . $this->port;
            }
        }

        return $authority;
    }

    /**
     * Get the user information
     *
     * @return string|null Returns the user information or null if not set.
     */
    public function getUserInfo()
    {
        return $this->userinfo;
    }

    /**
     * Get the host
     *
     * @return string|null Returns the host or null if not set.
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get the port
     *
     * @return string|null Returns the port or null if not set.
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Get the path
     *
     * @return string Returns the path which might be empty.
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the query component
     *
     * @return string|null Returns the query component or null if not set.
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Get the fragment identifier
     *
     * @return string|null Returns the fragment identifier or null if not set.
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Find out whether the IRI is absolute
     *
     * @return bool Returns true if the IRI is absolute, false otherwise.
     *
     * @api
     */
    public function isAbsolute()
    {
        return (null !== $this->scheme);
    }

    /**
     * Get as absolute IRI, i.e., without fragment identifier
     *
     * @return IRI The absolute IRI, i.e., without fragment identifier
     *
     * @throws \UnexpectedValueException If the IRI is a relative IRI.
     *
     * @link http://tools.ietf.org/html/rfc3987#section-2.2 RFC3987 absolute-IRI
     *
     * @api
     */
    public function getAbsoluteIri()
    {
        if (false === $this->isAbsolute()) {
            throw new \UnexpectedValueException('Cannot get the absolute IRI of a relative IRI.');
        }

        $absolute  = clone $this;
        $absolute->fragment = null;

        return $absolute;
    }

    /**
     * Check whether the passed IRI is equal
     *
     * @param IRI|string $iri IRI to compare to this instance.
     *
     * @return bool Returns true if the two IRIs are equal, false otherwise.
     *
     * @api
     */
    public function equals($iri)
    {
        // Make sure both instances are strings
        return ($this->__toString() === (string)$iri);
    }

    /**
     * Resolve a (relative) IRI reference against this IRI
     *
     * @param IRI|string $reference The (relative) IRI reference that should
     *                              be resolved against this IRI.
     *
     * @return IRI The resolved IRI.
     *
     * @throws \InvalidArgumentException If an invalid IRI is passed.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.2
     *
     * @api
     */
    public function resolve($reference)
    {
        $reference = new IRI($reference);

        $scheme = null;
        $authority = null;
        $path = '';
        $query = null;
        $fragment = null;

        // The Transform References algorithm as specified by RFC3986
        // see: http://tools.ietf.org/html/rfc3986#section-5.2.2
        if ($reference->scheme) {
            $scheme = $reference->scheme;
            $authority = $reference->getAuthority();
            $path = self::removeDotSegments($reference->path);
            $query = $reference->query;
        } else {
            if (null !== $reference->getAuthority()) {
                $authority = $reference->getAuthority();
                $path = self::removeDotSegments($reference->path);
                $query = $reference->query;
            } else {
                if (0 === strlen($reference->path)) {
                    $path = $this->path;
                    if (null !== $reference->query) {
                        $query = $reference->query;
                    } else {
                        $query = $this->query;
                    }
                } else {
                    if ('/' === $reference->path[0]) {
                        $path = self::removeDotSegments($reference->path);
                    } else {
                        // T.path = merge(Base.path, R.path);
                        if ((null !== $this->getAuthority()) && ('' === $this->path)) {
                            $path = '/' . $reference->path;
                        } else {
                            if (false !== ($end = strrpos($this->path, '/'))) {
                                $path = substr($this->path, 0, $end + 1);
                            }
                            $path .= $reference->path;
                        }
                        $path = self::removeDotSegments($path);
                    }
                    $query = $reference->query;
                }

                $authority = $this->getAuthority();
            }
            $scheme = $this->scheme;
        }

        $fragment = $reference->fragment;


        // The Component Recomposition algorithm as specified by RFC3986
        // see: http://tools.ietf.org/html/rfc3986#section-5.3
        $result = '';

        if ($scheme) {
            $result = $scheme . ':';
        }

        if (null !== $authority) {
            $result .= '//' . $authority;
        }

        $result .= $path;

        if (null !== $query) {
            $result .= '?' . $query;
        }

        if (null !== $fragment) {
            $result .= '#' . $fragment;
        }

        return new IRI($result);
    }

    /**
     * Transform this IRI to a IRI reference relative to the passed base IRI
     *
     * @param IRI|string $base The (relative) IRI reference that should be
     *                         be used as base IRI.
     * @param bool             Defines whether schema-relative IRIs such
     *                         as `//example.com` should be created (`true`)
     *                         or not (`false`).
     *
     * @return IRI The IRI reference relative to the passed base IRI.
     *
     * @throws \InvalidArgumentException If an invalid IRI is passed.
     *
     * @api
     */
    public function relativeTo($base, $schemaRelative = false)
    {
        if (false === ($base instanceof IRI)) {
            $base = new IRI($base);
        }
        $relative = clone $this;

        // Compare scheme
        if ($relative->scheme !== $base->scheme) {
            return $relative;
        }

        // Compare authority
        if ($relative->getAuthority() !== $base->getAuthority()) {
            if (true === $schemaRelative) {
                $relative->scheme = null;
            }

            return $relative;
        }
        $relative->scheme = null;
        $relative->host = null;
        $relative->userinfo = null;
        $relative->port = null;

        // Compare path
        $baseSegments     = explode('/', $base->path);
        $relativeSegments = explode('/', $relative->path);
        $len = min(count($baseSegments), count($relativeSegments)) - 1;  // do not move beyond last segment

        $pos = 0;

        while (($baseSegments[$pos] === $relativeSegments[$pos]) && ($pos < $len)) {
            $pos++;
        }

        $relative->path = '';
        $numBaseSegments = count($baseSegments) - $pos - 1;
        if ($numBaseSegments > 0) {
            $relative->path .= str_repeat('../', $numBaseSegments);
        }

        if (($baseSegments[$pos] !== $relativeSegments[$pos]) ||
            ((null === $relative->query) && (null === $relative->fragment))) {
            // if the two paths differ or if there's neither a query component nor a fragment,
            // we need to consider this IRI's path

            if (($relative->path === '') && (false !== strpos($relativeSegments[$pos], ':'))) {
                // if the first path segment contains a colon, we need to
                // prepend a ./ to distinguish it from an absolute IRI
                $relative->path .= './';
            }

            $relative->path .= implode('/', array_slice($relativeSegments, $pos));

            // .. and ensure that the resulting path isn't empty
            if (($relative->path === '')) {
                $relative->path .= './';
            }
        }

        if ($relative->query !== $base->query) {
            return $relative;
        }

        if (null !== $relative->fragment) {
            $relative->query = null;
        }

        return $relative;
    }

    /**
     * Convert an IRI to a relative IRI reference using this IRI as base
     *
     * This method provides a more convenient interface than the
     * {@link IRI::relativeTo()} method if the base IRI stays the same while
     * the IRIs to convert to relative IRI references change.
     *
     * @param  string|IRI $iri The IRI to convert to a relative reference
     * @param bool             Defines whether schema-relative IRIs such
     *                         as `//example.com` should be created (`true`)
     *                         or not (`false`).
     *
     * @throws \InvalidArgumentException If an invalid IRI is passed.
     *
     * @see \ML\IRI\IRI::relativeTo()
     *
     * @return IRI      The relative IRI reference
     */
    public function baseFor($iri, $schemaRelative = false)
    {
        if (false === ($iri instanceof IRI)) {
            $iri = new IRI($iri);
        }

        return $iri->relativeTo($this, $schemaRelative);
    }

    /**
     * Get a string representation of this IRI object
     *
     * @return string A string representation of this IRI instance.
     *
     * @api
     */
    public function __toString()
    {
        $result = '';

        if ($this->scheme) {
            $result .= $this->scheme . ':';
        }

        if (null !== ($authority = $this->getAuthority())) {
            $result .= '//' . $authority;
        }

        $result .= $this->path;

        if (null !== $this->query) {
            $result .= '?' . $this->query;
        }

        if (null !== $this->fragment) {
            $result .= '#' . $this->fragment;
        }

        return $result;
    }

    /**
     * Parse an IRI into it's components
     *
     * This is done according to
     * {@link http://tools.ietf.org/html/rfc3986#section-3.1 RFC3986}.
     *
     * @param string $iri The IRI to parse.
     */
    protected function parse($iri)
    {
        // Parse IRI by using the regular expression as specified by
        // http://tools.ietf.org/html/rfc3986#appendix-B
        $regex = '|^((?P<scheme>[^:/?#]+):)?' .
                    '((?P<doubleslash>//)(?P<authority>[^/?#]*))?(?P<path>[^?#]*)' .
                    '((?P<querydef>\?)(?P<query>[^#]*))?(#(?P<fragment>.*))?|';
        preg_match($regex, $iri, $match);

        // Extract scheme
        if (false === empty($match['scheme'])) {
            $this->scheme = $match['scheme'];
        }

        // Parse authority (http://tools.ietf.org/html/rfc3986#section-3.2)
        if ('//' === $match['doubleslash']) {
            if (0 === strlen($match['authority'])) {
                $this->host = '';
            } else {
                $authority = $match['authority'];

                // Split authority into userinfo and host
                // (use last @ to ignore unescaped @ symbols)
                if (false !== ($pos = strrpos($authority, '@'))) {
                    $this->userinfo = substr($authority, 0, $pos);
                    $authority = substr($authority, $pos + 1);
                }

                // Split authority into host and port
                $hostEnd = 0;
                if (('[' === $authority[0]) && (false !== ($pos = strpos($authority, ']')))) {
                    $hostEnd = $pos;
                }

                if ((false !== ($pos = strrpos($authority, ':'))) && ($pos > $hostEnd)) {
                    $this->host = substr($authority, 0, $pos);
                    $this->port = substr($authority, $pos + 1);
                } else {
                    $this->host = $authority;
                }
            }
        }

        // Extract path (http://tools.ietf.org/html/rfc3986#section-3.3)
        // The path is always present but might be empty
        $this->path = $match['path'];

        // Extract query (http://tools.ietf.org/html/rfc3986#section-3.4)
        if (false === empty($match['querydef'])) {
            $this->query = $match['query'];
        }

        // Extract fragment (http://tools.ietf.org/html/rfc3986#section-3.5)
        if (isset($match['fragment'])) {
            $this->fragment = $match['fragment'];
        }
    }

    /**
     * Remove dot-segments
     *
     * This method removes the special "." and ".." complete path segments
     * from an IRI.
     *
     * @param string $input The IRI from which dot segments should be removed.
     *
     * @return string The IRI with all dot-segments removed.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-5.2.4
     */
    private static function removeDotSegments($input)
    {
        $output = '';

        while (strlen($input) > 0) {
            if (('../' === substr($input, 0, 3)) || ('./' === substr($input, 0, 2))) {
                $input = substr($input, strpos($input, '/'));
            } elseif ('/./' === substr($input, 0, 3)) {
                $input = substr($input, 2);
            } elseif ('/.' === $input) {
                $input = '/';
            } elseif (('/../' === substr($input, 0, 4)) || ('/..' === $input)) {
                if ($input == '/..') {
                    $input = '/';
                } else {
                    $input = substr($input, 3);
                }

                if (false !== ($end = strrpos($output, '/'))) {
                    $output = substr($output, 0, $end);
                } else {
                    $output = '';
                }
            } elseif (('..' === $input) || ('.' === $input)) {
                $input = '';
            } else {
                if (false === ($end = strpos($input, '/', 1))) {
                    $output .= $input;
                    $input = '';
                } else {
                    $output .= substr($input, 0, $end);
                    $input = substr($input, $end);
                }
            }
        }
        return $output;
    }
}
