<?php

/*
 * (c) Markus Lanthaler <mail@markus-lanthaler.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ML\IRI\Test;

use ML\IRI\IRI;

/**
 * The IRI test suite.
 *
 * @author Markus Lanthaler <mail@markus-lanthaler.com>
 */
class IriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test parsing
     *
     * This test checks whether decomposing IRIs in their subcomponents works.
     *
     * @param string $iri           The IRI to decompose.
     * @param string|null $scheme   The scheme.
     * @param string|null $userinfo The user information.
     * @param string|null $host     The host.
     * @param string|null $port     The port.
     * @param string|null $path     The path.
     * @param string|null $query    The query component.
     * @param string|null $fragment The fragment identifier.
     *
     * @dataProvider decompositionProvider
     */
    public function testDecomposition($iri, $scheme, $userinfo, $host, $port, $path, $query, $fragment)
    {
        $iri = new IRI($iri);
        $test = new IRI($iri);  // test copy-constructor

        $this->assertEquals($scheme, $test->getScheme(), 'Scheme of ' . $iri);
        $this->assertEquals($userinfo, $test->getUserInfo(), 'User info of ' . $iri);
        $this->assertEquals($host, $test->getHost(), 'Host of ' . $iri);
        $this->assertEquals($port, $test->getPort(), 'Port of ' . $iri);
        $this->assertEquals($path, $test->getPath(), 'Path of ' . $iri);
        $this->assertEquals($query, $test->getQuery(), 'Query component of ' . $iri);
        $this->assertEquals($fragment, $test->getFragment(), 'Fragment of ' . $iri);
        $this->assertEquals($iri, $test->__toString(), 'Recomposition of ' . $iri);
        $this->assertTrue($test->equals($iri), 'Test equality of parsed ' . $iri);
    }

    /**
     * Decomposition test cases
     *
     * These test cases were taken from the
     * {@link http://tools.ietf.org/html/rfc3986#section-1.1.2 URI specification}
     * and from {@link http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html}.
     *
     * @return array The decomposition test cases.
     */
    public function decompositionProvider()
    {
        return array(  //$iri, $scheme, $userinfo, $host, $port, $path, $query, $fragment
            // http://tools.ietf.org/html/rfc3986#section-1.1.2
            array('ftp://ftp.is.co.za/rfc/rfc1808.txt', 'ftp', null, 'ftp.is.co.za', null, '/rfc/rfc1808.txt', null, null),
            array('http://www.ietf.org/rfc/rfc2396.txt#frag', 'http', null, 'www.ietf.org', null, '/rfc/rfc2396.txt', null, 'frag'),
            array('ldap://[2001:db8::7]/c=GB?objectClass?one', 'ldap', null, '[2001:db8::7]', null, '/c=GB', 'objectClass?one', null),
            array('mailto:John.Doe@example.com', 'mailto', null, null, null, 'John.Doe@example.com', null, null),
            array('news:comp.infosystems.www.servers.unix', 'news', null, null, null, 'comp.infosystems.www.servers.unix', null, null),
            array('tel:+1-816-555-1212', 'tel', null, null, null, '+1-816-555-1212', null, null),
            array('telnet://192.0.2.16:80/', 'telnet', null, '192.0.2.16', '80', '/', null, null),
            array('urn:oasis:names:specification:docbook:dtd:xml:4.1.2', 'urn', null, null, null, 'oasis:names:specification:docbook:dtd:xml:4.1.2', null, null),
            // http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html
            array('http://user:pass@example.org:99/aaa/bbb?qqq#fff', 'http', 'user:pass', 'example.org', '99', '/aaa/bbb' , 'qqq', 'fff'),
            // INVALID IRI array('http://user:pass@example.org:99aaa/bbb'),
            array('http://user:pass@example.org:99?aaa/bbb', 'http', 'user:pass', 'example.org', '99', '', 'aaa/bbb', null),
            array('http://user:pass@example.org:99#aaa/bbb', 'http', 'user:pass', 'example.org', '99' , '', null, 'aaa/bbb'),
            array('http://example.com?query', 'http', null, 'example.com', null, '', 'query', null)
        );
    }

    /**
     * Test whether parsing invalid values leads to an exception
     *
     * @expectedException InvalidArgumentException
     */
    public function testParseInvalidValue()
    {
        new IRI(2);
    }

    /**
     * Test whether an IRI is an absolute IRI or a relative one
     *
     * @param string $iri        The IRI to test.
     * @param bool   $isAbsolute True if the IRI is absolute, false otherwise.
     *
     * @dataProvider isAbsoluteProvider
     */
    public function testIsAbsolute($iri, $isAbsolute)
    {
        $iri = new IRI($iri);
        $this->assertEquals($isAbsolute, $iri->isAbsolute());
    }


    /**
     * Absolute/relative IRI test cases
     *
     * These tests were taken from the
     * {@link http://tools.ietf.org/html/rfc3986#section-5.4 URI specification} and from
     * {@link http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html}.
     *
     * @return array The absolute/relative IRI test cases.
     */
    public function isAbsoluteProvider()
    {
        return array(
            // http://tools.ietf.org/html/rfc3986#section-5.4
            array('http:g', true),
            array('g:h', true),
            array('g', false),
            array('./g', false),
            array('g/', false),
            array('/g', false),
            array('//g', false),
            array('?y', false),
            array('g?y', false),
            array('#s', false),
            array('g#s', false),
            array('g?y#s', false),
            array(';x', false),
            array('g;x', false),
            array('g;x?y#s', false),
            array('', false),
            array('.', false),
            array('./', false),
            array('..', false),
            array('../', false),
            array('../g', false),
            array('../..', false),
            array('../../', false),
            array('../../g', false),
            array('../../../g', false),
            array('../../../../g', false),
            array('/./g', false),
            array('/../g', false),
            array('g.', false),
            array('.g', false),
            array('g..', false),
            array('..g', false),
            array('./../g', false),
            array('./g/.', false),
            array('g/./h', false),
            array('g/../h', false),
            array('g;x=1/./y', false),
            array('g;x=1/../y', false),
            array('g?y/./x', false),
            array('g?y/../x', false),
            array('g#s/./x', false),
            array('g#s/../x', false),
            // http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html
            array('http://example.org/aaa/bbb#ccc', true),
            array('mailto:local@domain.org', true),
            array('mailto:local@domain.org#frag', true),
            array('HTTP://EXAMPLE.ORG/AAA/BBB#CCC', true),
            array('http://example.org/aaa%2fbbb#ccc', true),
            array('http://example.org/aaa%2Fbbb#ccc', true),
            array('http://example.org:80/aaa/bbb#ccc', true),
            array('http://example.org:/aaa/bbb#ccc', true),
            array('http://example.org./aaa/bbb#ccc', true),
            array('http://example.123./aaa/bbb#ccc', true),
            array('http://example.org', true),
            array('http://[FEDC:BA98:7654:3210:FEDC:BA98:7654:3210]:80/index.html', true),
            array('http://[1080:0:0:0:8:800:200C:417A]/index.html', true),
            array('http://[3ffe:2a00:100:7031::1]', true),
            array('http://[1080::8:800:200C:417A]/foo', true),
            array('http://[::192.9.5.5]/ipng', true),
            array('http://[::FFFF:129.144.52.38]:80/index.html', true),
            array('http://[2010:836B:4179::836B:4179]', true),
            array('http://example/Andr&#567;', true),
            array('file:///C:/DEV/Haskell/lib/HXmlToolbox-3.01/examples/', true),
            array('http://46229EFFE16A9BD60B9F1BE88B2DB047ADDED785/demo.mp3', true),
            array('//example.org/aaa/bbb#ccc', false),
            array('/aaa/bbb#ccc', false),
            array('bbb#ccc', false),
            array('#ccc', false),
            array('#', false),
            array('/', false),
            array('%2F', false),
            array('aaa%2Fbbb', false),
            array('//[2010:836B:4179::836B:4179]', false),
            array("A'C", false),
            array('A$C', false),
            array('A@C', false),
            array('"A,C"', false)
        );
    }

    /**
     * Test conversion to absolute IRI, i.e., removal of the fragment
     */
    public function testGetAbsoluteIri()
    {
        $iri = new IRI('http://example.org/aaa%2fbbb#ccc');
        $this->assertEquals('http://example.org/aaa%2fbbb', (string) $iri->getAbsoluteIri());

        $iri = new IRI('http://example.org/iri#with-fragment/looking/like/a/path?and&query');
        $this->assertEquals('http://example.org/iri', (string) $iri->getAbsoluteIri());
    }

    /**
     * Test conversion to absolute IRI for a relative IRI
     *
     * @expectedException UnexpectedValueException
     */
    public function testGetAbsoluteIriOnRelativeIri()
    {
        $iri = new IRI('/relative#with-fragment');
        $iri->getAbsoluteIri();
    }

    /**
     * Test relative reference resolution
     *
     * @param string $base      The base IRI.
     * @param string $reference The reference to resolve.
     * @param string $expected  The expected absolute IRI.
     *
     * @dataProvider referenceResolutionProvider
     */
    public function testReferenceResolution($base, $reference, $expected)
    {
        $base = new IRI($base);
        $this->assertEquals($expected, (string)$base->resolve($reference));
    }

    /**
     * Reference resolution test cases
     *
     * These test cases were taken from the
     * {@link http://tools.ietf.org/html/rfc3986#section-5.4 URI specification},
     * from {@link http://www.w3.org/2004/04/uri-rel-test.html},
     * {@link http://dig.csail.mit.edu/2005/ajar/ajaw/test/uri-test-doc.html},
     * {@link http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html},
     * and {@link http://greenbytes.de/tech/tc/uris/}.
     *
     * @return array The reference resolution test cases.
     */
    public function referenceResolutionProvider()
    {
        return array(  // $base, $relative, $absolute
            array('', '../a/b', '/a/b'),
            array('', '/.', '/'),
            array(null, '', ''),
            array('', null, ''),
            array(null, null, ''),
            // http://tools.ietf.org/html/rfc3986#section-5.4
            array('http://a/b/c/d;p?q', 'g:h', 'g:h'),
            array('http://a/b/c/d;p?q', 'g', 'http://a/b/c/g'),
            array('http://a/b/c/d;p?q', './g', 'http://a/b/c/g'),
            array('http://a/b/c/d;p?q', 'g/', 'http://a/b/c/g/'),
            array('http://a/b/c/d;p?q', '/g', 'http://a/g'),
            array('http://a/b/c/d;p?q', '//g', 'http://g'),
            array('http://a/b/c/d;p?q', '?y', 'http://a/b/c/d;p?y'),
            array('http://a/b/c/d;p?q', 'g?y', 'http://a/b/c/g?y'),
            array('http://a/b/c/d;p?q', '#s', 'http://a/b/c/d;p?q#s'),
            array('http://a/b/c/d;p?q', 'g#s', 'http://a/b/c/g#s'),
            array('http://a/b/c/d;p?q', 'g?y#s', 'http://a/b/c/g?y#s'),
            array('http://a/b/c/d;p?q', ';x', 'http://a/b/c/;x'),
            array('http://a/b/c/d;p?q', 'g;x', 'http://a/b/c/g;x'),
            array('http://a/b/c/d;p?q', 'g;x?y#s', 'http://a/b/c/g;x?y#s'),
            array('http://a/b/c/d;p?q', '', 'http://a/b/c/d;p?q'),
            array('http://a/b/c/d;p?q', '.', 'http://a/b/c/'),
            array('http://a/b/c/d;p?q', './', 'http://a/b/c/'),
            array('http://a/b/c/d;p?q', '..', 'http://a/b/'),
            array('http://a/b/c/d;p?q', '../', 'http://a/b/'),
            array('http://a/b/c/d;p?q', '../g', 'http://a/b/g'),
            array('http://a/b/c/d;p?q', '../..', 'http://a/'),
            array('http://a/b/c/d;p?q', '../../', 'http://a/'),
            array('http://a/b/c/d;p?q', '../../g', 'http://a/g'),
            array('http://a/b/c/d;p?q', '../../../g', 'http://a/g'),
            array('http://a/b/c/d;p?q', '../../../../g', 'http://a/g'),
            array('http://a/b/c/d;p?q', '/./g', 'http://a/g'),
            array('http://a/b/c/d;p?q', '/../g', 'http://a/g'),
            array('http://a/b/c/d;p?q', 'g.', 'http://a/b/c/g.'),
            array('http://a/b/c/d;p?q', '.g', 'http://a/b/c/.g'),
            array('http://a/b/c/d;p?q', 'g..', 'http://a/b/c/g..'),
            array('http://a/b/c/d;p?q', '..g', 'http://a/b/c/..g'),
            array('http://a/b/c/d;p?q', './../g', 'http://a/b/g'),
            array('http://a/b/c/d;p?q', './g/.', 'http://a/b/c/g/'),
            array('http://a/b/c/d;p?q', 'g/./h', 'http://a/b/c/g/h'),
            array('http://a/b/c/d;p?q', 'g/../h', 'http://a/b/c/h'),
            array('http://a/b/c/d;p?q', 'g;x=1/./y', 'http://a/b/c/g;x=1/y'),
            array('http://a/b/c/d;p?q', 'g;x=1/../y', 'http://a/b/c/y'),
            array('http://a/b/c/d;p?q', 'g?y/./x', 'http://a/b/c/g?y/./x'),
            array('http://a/b/c/d;p?q', 'g?y/../x', 'http://a/b/c/g?y/../x'),
            array('http://a/b/c/d;p?q', 'g#s/./x', 'http://a/b/c/g#s/./x'),
            array('http://a/b/c/d;p?q', 'g#s/../x', 'http://a/b/c/g#s/../x'),
            array('http://a/b/c/d;p?q', 'http:g', 'http:g'),
            // http://www.w3.org/2004/04/uri-rel-test.html
            array('http://a/b/c/d;p?q', './g:h', 'http://a/b/c/g:h'),
            // http://dig.csail.mit.edu/2005/ajar/ajaw/test/uri-test-doc.html},
            // http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html}.
            array('foo:xyz', 'bar:abc', 'bar:abc'),
            array('http://example/x/y/z', '../abc', 'http://example/x/abc'),
            array('http://example2/x/y/z', '//example/x/abc', 'http://example/x/abc'),
            array('http://example2/x/y/z', 'http://example/x/abc', 'http://example/x/abc'),
            array('http://ex/x/y/z', '../r', 'http://ex/x/r'),
            array('http://ex/x/y/z', '/r', 'http://ex/r'),
            array('http://ex/x/y/z', 'q/r', 'http://ex/x/y/q/r'),
            array('http://ex/x/y', 'q/r#s', 'http://ex/x/q/r#s'),
            array('http://ex/x/y', 'q/r#s/t', 'http://ex/x/q/r#s/t'),
            array('http://ex/x/y', 'ftp://ex/x/q/r', 'ftp://ex/x/q/r'),
            array('http://ex/x/y', '', 'http://ex/x/y'),
            array('http://ex/x/y/', '', 'http://ex/x/y/'),
            array('http://ex/x/y/pdq', '', 'http://ex/x/y/pdq'),
            array('http://ex/x/y/', 'z/', 'http://ex/x/y/z/'),
            array('file:/swap/test/animal.rdf', '#Animal', 'file:/swap/test/animal.rdf#Animal'),
            array('file:/e/x/y/z', '../abc', 'file:/e/x/abc'),
            array('file:/example2/x/y/z', '/example/x/abc', 'file:/example/x/abc'),
            array('file:/ex/x/y/z', '../r', 'file:/ex/x/r'),
            array('file:/ex/x/y/z', '/r', 'file:/r'),
            array('file:/ex/x/y', 'q/r', 'file:/ex/x/q/r'),
            array('file:/ex/x/y', 'q/r#s', 'file:/ex/x/q/r#s'),
            array('file:/ex/x/y', 'q/r#', 'file:/ex/x/q/r#'),
            array('file:/ex/x/y', 'q/r#s/t', 'file:/ex/x/q/r#s/t'),
            array('file:/ex/x/y', 'ftp://ex/x/q/r', 'ftp://ex/x/q/r'),
            array('file:/ex/x/y', '', 'file:/ex/x/y'),
            array('file:/ex/x/y/', '', 'file:/ex/x/y/'),
            array('file:/ex/x/y/pdq', '', 'file:/ex/x/y/pdq'),
            array('file:/ex/x/y/', 'z/', 'file:/ex/x/y/z/'),
            array('file:/devel/WWW/2000/10/swap/test/reluri-1.n3', '//meetings.example.com/cal#m1', 'file://meetings.example.com/cal#m1'),
            array('file:/home/connolly/w3ccvs/WWW/2000/10/swap/test/reluri-1.n3', '//meetings.example.com/cal#m1', 'file://meetings.example.com/cal#m1'),
            array('file:/some/dir/foo', './#blort', 'file:/some/dir/#blort'),
            array('file:/some/dir/foo', './#', 'file:/some/dir/#'),
            array('http://ex/x/y', './q:r', 'http://ex/x/q:r'),
            array('http://ex/x/y', './p=q:r', 'http://ex/x/p=q:r'),
            array('http://ex/x/y?pp/qq', '?pp/rr', 'http://ex/x/y?pp/rr'),
            array('http://ex/x/y?pp/qq', 'y/z', 'http://ex/x/y/z'),
            array('mailto:local', 'local/qual@domain.org#frag', 'mailto:local/qual@domain.org#frag'),
            array('mailto:local/qual1@domain1.org', 'more/qual2@domain2.org#frag', 'mailto:local/more/qual2@domain2.org#frag'),
            array('http://ex/x/z?q', 'y?q', 'http://ex/x/y?q'),
            array('http://ex?p', '/x/y?q', 'http://ex/x/y?q'),
            array('foo:a/b', 'c/d', 'foo:a/c/d'),
            array('foo:a/b', '/c/d', 'foo:/c/d'),
            array('foo:a/b?c#d', '', 'foo:a/b?c'),
            array('foo:a', 'b/c', 'foo:b/c'),
            array('foo:/a/y/z', '../b/c', 'foo:/a/b/c'),
            // TODO Check this test, shouldn't the result be foo:/b/c? array('foo:a', './b/c', 'foo:b/c'),
            array('foo:a', '/./b/c', 'foo:/b/c'),
            array('foo://a//b/c', '../../d', 'foo://a/d'),
            array('foo:a', '.', 'foo:'),
            array('foo:a', '..', 'foo:'),
            array('http://example/x/y%2Fz', 'abc', 'http://example/x/abc'),
            array('http://example/a/x/y/z', '../../x%2Fabc', 'http://example/a/x%2Fabc'),
            array('http://example/a/x/y%2Fz', '../x%2Fabc', 'http://example/a/x%2Fabc'),
            array('http://example/x%2Fy/z', 'abc', 'http://example/x%2Fy/abc'),
            array('http://ex/x/y', 'q%3Ar', 'http://ex/x/q%3Ar'),
            array('http://example/x/y%2Fz', '/x%2Fabc', 'http://example/x%2Fabc'),
            array('http://example/x/y/z', '/x%2Fabc', 'http://example/x%2Fabc'),
            array('http://example/x/y%2Fz', '/x%2Fabc', 'http://example/x%2Fabc'),
            array('ftp://example/x/y', 'http://example/a/b/../../c', 'http://example/c'),
            array('ftp://example/x/y', 'http://example/a/b/c/../../', 'http://example/a/'),
            array('ftp://example/x/y', 'http://example/a/b/c/./', 'http://example/a/b/c/'),
            array('ftp://example/x/y', 'http://example/a/b/c/.././', 'http://example/a/b/'),
            array('ftp://example/x/y', 'http://example/a/b/c/d/../../../../e', 'http://example/e'),
            array('ftp://example/x/y', 'http://example/a/b/c/d/../.././../../e', 'http://example/e'),
            array('mailto:local1@domain1?query1', 'local2@domain2', 'mailto:local2@domain2'),
            array('mailto:local1@domain1', 'local2@domain2?query2', 'mailto:local2@domain2?query2'),
            array('mailto:local1@domain1?query1', 'local2@domain2?query2', 'mailto:local2@domain2?query2'),
            array('mailto:local@domain?query1', '?query2', 'mailto:local@domain?query2'),
            array('mailto:?query1', 'local@domain?query2', 'mailto:local@domain?query2'),
            array('mailto:local@domain?query1', '?query2', 'mailto:local@domain?query2'),
            array('foo:bar', 'http://example/a/b?c/../d', 'http://example/a/b?c/../d'),
            array('foo:bar', 'http://example/a/b#c/../d', 'http://example/a/b#c/../d'),
            array('http://example.org/base/uri', 'this', 'http://example.org/base/this'),  // Fixed absolute from http:this
            array('http://example.org/base/uri', 'http:this', 'http:this'),
            array('http:base', 'http:this', 'http:this'),
            array('f:/a', './/g', 'f://g'),
            array('f://example.org/base/a', 'b/c//d/e', 'f://example.org/base/b/c//d/e'),
            array('mid:m@example.ord/c@example.org', 'm2@example.ord/c2@example.org', 'mid:m@example.ord/m2@example.ord/c2@example.org'),
            array('file:///C:/DEV/Haskell/lib/HXmlToolbox-3.01/examples/', 'mini1.xml', 'file:///C:/DEV/Haskell/lib/HXmlToolbox-3.01/examples/mini1.xml'),
            array('foo:a/y/z', '../b/c', 'foo:a/b/c'),
            array('http://ex', '/x/y?q', 'http://ex/x/y?q'),
            array('http://ex', 'x/y?q', 'http://ex/x/y?q'),
            array('http://ex?p', '/x/y?q', 'http://ex/x/y?q'),
            array('http://ex?p', 'x/y?q', 'http://ex/x/y?q'),
            array('http://ex#f', '/x/y?q', 'http://ex/x/y?q'),
            array('http://ex#f', 'x/y?q', 'http://ex/x/y?q'),
            array('http://ex?p', '/x/y#g', 'http://ex/x/y#g'),
            array('http://ex?p', 'x/y#g', 'http://ex/x/y#g'),
            array('http://ex', '/', 'http://ex/'),
            array('http://ex', './', 'http://ex/'),
            array('http://ex', '/a/b', 'http://ex/a/b'),
            array('http://ex/a/b', './', 'http://ex/a/'),
            array('mailto:local/option@domain.org?notaquery#frag', 'more@domain', 'mailto:local/more@domain'),
            array('mailto:local/option@domain.org?notaquery#frag', '#newfrag', 'mailto:local/option@domain.org?notaquery#newfrag'),
            array('mailto:local/option@domain.org?notaquery#frag', 'l1/q1@domain', 'mailto:local/l1/q1@domain'),
            array('mailto:local1@domain1?query1', 'mailto:local2@domain2', 'mailto:local2@domain2'),
            array('mailto:local1@domain1', 'mailto:local2@domain2?query2', 'mailto:local2@domain2?query2'),
            array('mailto:local1@domain1?query1', 'mailto:local2@domain2?query2', 'mailto:local2@domain2?query2'),
            array('mailto:local@domain?query1', 'mailto:local@domain?query2', 'mailto:local@domain?query2'),
            array('mailto:?query1', 'mailto:local@domain?query2', 'mailto:local@domain?query2'),
            array('mailto:local@domain?query1', '?query2', 'mailto:local@domain?query2'),
            array('info:name/1234/../567', 'name/9876/../543', 'info:name/name/543'),
            array('info:/name/1234/../567', 'name/9876/../543', 'info:/name/name/543'),
            array('http://ex/x/y', 'q/r', 'http://ex/x/q/r'),
            array('file:/devel/WWW/2000/10/swap/test/reluri-1.n3', 'file://meetings.example.com/cal#m1', 'file://meetings.example.com/cal#m1'),
            array('file:/home/connolly/w3ccvs/WWW/2000/10/swap/test/reluri-1.n3', 'file://meetings.example.com/cal#m1', 'file://meetings.example.com/cal#m1'),
            array('http://example/x/abc.efg', './', 'http://example/x/'),
            array('http://www.w3.org/People/Berners-Lee/card.rdf', '../../2002/01/tr-automation/tr.rdf', 'http://www.w3.org/2002/01/tr-automation/tr.rdf'),
            array('http://example.com/', '.', 'http://example.com/'),
            array('http://example.com/.meta.n3', '.meta.n3', 'http://example.com/.meta.n3'),
            // http://greenbytes.de/tech/tc/uris/
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'http://a/b/c/d;p?q', 'http://a/b/c/d;p?q'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g:h', 'g:h'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '', 'data:text/plain;charset=iso-8859-7,%be%fg%be'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g', 'data:text/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', './g', 'data:text/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g/', 'data:text/g/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '/g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '//g', 'data://g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '?y', 'data:text/plain;charset=iso-8859-7,%be%fg%be?y'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g?y', 'data:text/g?y'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '#s', 'data:text/plain;charset=iso-8859-7,%be%fg%be#s'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g#s', 'data:text/g#s'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g?y#s', 'data:text/g?y#s'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', ';x', 'data:text/;x'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g;x', 'data:text/g;x'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g;x?y#s', 'data:text/g;x?y#s'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '', 'data:text/plain;charset=iso-8859-7,%be%fg%be'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '.', 'data:text/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', './', 'data:text/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '..', 'data:/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../', 'data:/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../..', 'data:/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../../', 'data:/'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../../g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../../../g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '../../../../g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '/./g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '/../g', 'data:/g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g.', 'data:text/g.'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '.g', 'data:text/.g'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'g..', 'data:text/g..'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', '..g', 'data:text/..g'),
            array('http://a/b/c/d;p?q', 'data:image/gif;base64,R0lGODdhMAAwAPAAAAAAAP///ywAAAAAMAAwAAAC8IyPqcvt3wCcDkiLc7C0qwyGHhSWpjQu5yqmCYsapyuvUUlvONmOZtfzgFzByTB10QgxOR0TqBQejhRNzOfkVJ+5YiUqrXF5Y5lKh/DeuNcP5yLWGsEbtLiOSpa/TPg7JpJHxyendzWTBfX0cxOnKPjgBzi4diinWGdkF8kjdfnycQZXZeYGejmJlZeGl9i2icVqaNVailT6F5iJ90m6mvuTS4OK05M0vDk0Q4XUtwvKOzrcd3iq9uisF81M1OIcR7lEewwcLp7tuNNkM3uNna3F2JQFo97Vriy/Xl4/f1cf5VWzXyym7PHhhx4dbgYKAAA7', 'data:image/gif;base64,R0lGODdhMAAwAPAAAAAAAP///ywAAAAAMAAwAAAC8IyPqcvt3wCcDkiLc7C0qwyGHhSWpjQu5yqmCYsapyuvUUlvONmOZtfzgFzByTB10QgxOR0TqBQejhRNzOfkVJ+5YiUqrXF5Y5lKh/DeuNcP5yLWGsEbtLiOSpa/TPg7JpJHxyendzWTBfX0cxOnKPjgBzi4diinWGdkF8kjdfnycQZXZeYGejmJlZeGl9i2icVqaNVailT6F5iJ90m6mvuTS4OK05M0vDk0Q4XUtwvKOzrcd3iq9uisF81M1OIcR7lEewwcLp7tuNNkM3uNna3F2JQFo97Vriy/Xl4/f1cf5VWzXyym7PHhhx4dbgYKAAA7'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'data:image/gif;base64,R0lGODdhMAAwAPAAAAAAAP///ywAAAAAMAAwAAAC8IyPqcvt3wCcDkiLc7C0qwyGHhSWpjQu5yqmCYsapyuvUUlvONmOZtfzgFzByTB10QgxOR0TqBQejhRNzOfkVJ+5YiUqrXF5Y5lKh/DeuNcP5yLWGsEbtLiOSpa/TPg7JpJHxyendzWTBfX0cxOnKPjgBzi4diinWGdkF8kjdfnycQZXZeYGejmJlZeGl9i2icVqaNVailT6F5iJ90m6mvuTS4OK05M0vDk0Q4XUtwvKOzrcd3iq9uisF81M1OIcR7lEewwcLp7tuNNkM3uNna3F2JQFo97Vriy/Xl4/f1cf5VWzXyym7PHhhx4dbgYKAAA7', 'data:image/gif;base64,R0lGODdhMAAwAPAAAAAAAP///ywAAAAAMAAwAAAC8IyPqcvt3wCcDkiLc7C0qwyGHhSWpjQu5yqmCYsapyuvUUlvONmOZtfzgFzByTB10QgxOR0TqBQejhRNzOfkVJ+5YiUqrXF5Y5lKh/DeuNcP5yLWGsEbtLiOSpa/TPg7JpJHxyendzWTBfX0cxOnKPjgBzi4diinWGdkF8kjdfnycQZXZeYGejmJlZeGl9i2icVqaNVailT6F5iJ90m6mvuTS4OK05M0vDk0Q4XUtwvKOzrcd3iq9uisF81M1OIcR7lEewwcLp7tuNNkM3uNna3F2JQFo97Vriy/Xl4/f1cf5VWzXyym7PHhhx4dbgYKAAA7'),
            array('http://a/b/c/d;p?q', 'data:text/plain;charset=iso-8859-7,%be%fg%be', 'data:text/plain;charset=iso-8859-7,%be%fg%be'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'data:text/plain;charset=iso-8859-7,%be%fg%be', 'data:text/plain;charset=iso-8859-7,%be%fg%be'),
            array('http://a/b/c/d;p?q', 'http://www.example.org/Dürst', 'http://www.example.org/Dürst'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'http://www.example.org/Dürst', 'http://www.example.org/Dürst'),
            array('http://a/b/c/d;p?q', 'http://www.example.org/foo bar/qux<>?\^`{|}', 'http://www.example.org/foo bar/qux<>?\^`{|}'),
            array('data:text/plain;charset=iso-8859-7,%be%fg%be', 'http://www.example.org/foo bar/qux<>?\^`{|}', 'http://www.example.org/foo bar/qux<>?\^`{|}'),
            array('http://example.com/b;bar', ';foo', 'http://example.com/;foo'),
            array('http://1.example.org/path1/file1.ext', 'http://2.example.org#frag2', 'http://2.example.org#frag2'),
            array('http://example.org/a/b', '?x', 'http://example.org/a/b?x'),
            array('http://example.org/foo/bar', 'http:test', 'http:test'),
            array('http://www.example.com/#', 'hello, world', 'http://www.example.com/hello, world'),
            array('http://www.example.com/#', '%c2%a9', 'http://www.example.com/%c2%a9'),
            array('http://www.example.com/#', '%41%a', 'http://www.example.com/%41%a'),
            array('http://www.example.com/#', 'asdf#qwer', 'http://www.example.com/asdf#qwer'),
            array('http://www.example.com/#', '#asdf', 'http://www.example.com/#asdf'),
            array('http://www.example.com/foo/bar', 'file:c:\\\\foo\\\\bar.html', 'file:c:\\\\foo\\\\bar.html'),
            array('http://www.example.com/foo/bar', 'File:c|////foo\\\\bar.html', 'File:c|////foo\\\\bar.html'),
            array('http://www.example.com/foo/bar', 'file:', 'file:'),
            array('http://www.example.com/foo/bar', 'file:UNChost/path', 'file:UNChost/path'),
            array('http://www.example.com/foo/bar', 'c:\\\\foo\\\\bar', 'c:\\\\foo\\\\bar'),
            array('http://www.example.com/foo/bar', 'C|/foo/bar', 'http://www.example.com/foo/C|/foo/bar'),
            array('http://www.example.com/foo/bar', '/C|\\\\foo\\\\bar', 'http://www.example.com/C|\\\\foo\\\\bar'),
            array('http://www.example.com/foo/bar', '//C|/foo/bar', 'http://C|/foo/bar'),
            array('http://www.example.com/foo/bar', '//server/file', 'http://server/file'),
            array('http://www.example.com/foo/bar', '\\\\\\\\server\\\\file', 'http://www.example.com/foo/\\\\\\\\server\\\\file'),
            array('http://www.example.com/foo/bar', '/\\\\server/file', 'http://www.example.com/\\\\server/file'),
            array('http://www.example.com/foo/bar', 'file:c:foo/bar.html', 'file:c:foo/bar.html'),
            array('http://www.example.com/foo/bar', 'file:/\\\\/\\\\C:\\\\\\\\//foo\\\\bar.html', 'file:/\\\\/\\\\C:\\\\\\\\//foo\\\\bar.html'),
            array('http://www.example.com/foo/bar', 'file:///foo/bar.txt', 'file:///foo/bar.txt'),
            array('http://www.example.com/foo/bar', 'FILE:/\\\\/\\\\7:\\\\\\\\//foo\\\\bar.html', 'FILE:/\\\\/\\\\7:\\\\\\\\//foo\\\\bar.html'),
            array('http://www.example.com/foo/bar', 'file:filer/home\\\\me', 'file:filer/home\\\\me'),
            array('http://www.example.com/foo/bar', 'file:///C:/foo/../../../bar.html', 'file:///bar.html'),
            array('http://www.example.com/foo/bar', 'file:///C:/asdf#\\%c2', 'file:///C:/asdf#\\%c2'),
            array('http://www.example.com/foo/bar', 'file:///home/me', 'file:///home/me'),
            array('http://www.example.com/foo/bar', 'file:c:\\\\foo\\\\bar.html', 'file:c:\\\\foo\\\\bar.html'),
            array('http://www.example.com/foo/bar', 'file:c|//foo\\\\bar.html', 'file:c|//foo\\\\bar.html'),
            array('http://www.example.com/foo/bar', '//', 'http://'),
            array('http://www.example.com/foo/bar', '///', 'http:///'),
            array('http://www.example.com/foo/bar', '///test', 'http:///test'),
            array('http://www.example.com/foo/bar', 'file://test', 'file://test'),
            array('http://www.example.com/foo/bar', 'file://localhost/', 'file://localhost/'),
            array('http://www.example.com/foo/bar', 'file://localhost/test', 'file://localhost/test'),
            array('file:///tmp/mock/path', 'file:c:\\\\foo\\\\bar.html', 'file:c:\\\\foo\\\\bar.html'),
            array('file:///tmp/mock/path', 'File:c|////foo\\\\bar.html', 'File:c|////foo\\\\bar.html'),
            array('file:///tmp/mock/path', 'file:', 'file:'),
            array('file:///tmp/mock/path', 'file:UNChost/path', 'file:UNChost/path'),
            array('file:///tmp/mock/path', 'c:\\\\foo\\\\bar', 'c:\\\\foo\\\\bar'),
            array('file:///tmp/mock/path', 'C|/foo/bar', 'file:///tmp/mock/C|/foo/bar'),
            array('file:///tmp/mock/path', '/C|\\\\foo\\\\bar', 'file:///C|\\\\foo\\\\bar'),
            array('file:///tmp/mock/path', '//C|/foo/bar', 'file://C|/foo/bar'),
            array('file:///tmp/mock/path', '//server/file', 'file://server/file'),
            array('file:///tmp/mock/path', '\\\\\\\\server\\\\file', 'file:///tmp/mock/\\\\\\\\server\\\\file'),
            array('file:///tmp/mock/path', '/\\\\server/file', 'file:///\\\\server/file'),
            array('file:///tmp/mock/path', 'file:c:foo/bar.html', 'file:c:foo/bar.html'),
            array('file:///tmp/mock/path', 'file:/\\\\/\\\\C:\\\\\\\\//foo\\\\bar.html', 'file:/\\\\/\\\\C:\\\\\\\\//foo\\\\bar.html'),
            array('file:///tmp/mock/path', 'file:///foo/bar.txt', 'file:///foo/bar.txt'),
            array('file:///tmp/mock/path', 'FILE:/\\\\/\\\\7:\\\\\\\\//foo\\\\bar.html', 'FILE:/\\\\/\\\\7:\\\\\\\\//foo\\\\bar.html'),
            array('file:///tmp/mock/path', 'file:filer/home\\\\me', 'file:filer/home\\\\me'),
            array('file:///tmp/mock/path', 'file:///C:/foo/../../../bar.html', 'file:///bar.html'),
            array('file:///tmp/mock/path', 'file:///C:/asdf#\\%c2', 'file:///C:/asdf#\\%c2'),
            array('file:///tmp/mock/path', 'file:///home/me', 'file:///home/me'),
            array('file:///tmp/mock/path', 'file:c:\\\\foo\\\\bar.html', 'file:c:\\\\foo\\\\bar.html'),
            array('file:///tmp/mock/path', 'file:c|//foo\\\\bar.html', 'file:c|//foo\\\\bar.html'),
            array('file:///tmp/mock/path', '//', 'file://'),
            array('file:///tmp/mock/path', '///', 'file:///'),
            array('file:///tmp/mock/path', '///test', 'file:///test'),
            array('file:///tmp/mock/path', 'file://test', 'file://test'),
            array('file:///tmp/mock/path', 'file://localhost/', 'file://localhost/'),
            array('file:///tmp/mock/path', 'file://localhost/test', 'file://localhost/test'),
            array('http://', 'GoOgLe.CoM', 'http:///GoOgLe.CoM'),
            array('http://', 'Goo%20 goo%7C|.com', 'http:///Goo%20 goo%7C|.com'),
            array('http://', '%ef%b7%90zyx.com', 'http:///%ef%b7%90zyx.com'),
            array('http://', '%ef%bc%85%ef%bc%94%ef%bc%91.com', 'http:///%ef%bc%85%ef%bc%94%ef%bc%91.com'),
            array('http://', '%ef%bc%85%ef%bc%90%ef%bc%90.com', 'http:///%ef%bc%85%ef%bc%90%ef%bc%90.com'),
            array('http://', '%zz%66%a', 'http:///%zz%66%a'),
            array('http://', '%25', 'http:///%25'),
            array('http://', 'hello%00', 'http:///hello%00'),
            array('http://', '%30%78%63%30%2e%30%32%35%30.01', 'http:///%30%78%63%30%2e%30%32%35%30.01'),
            array('http://', '%30%78%63%30%2e%30%32%35%30.01%2e', 'http:///%30%78%63%30%2e%30%32%35%30.01%2e'),
            array('http://', '%3g%78%63%30%2e%30%32%35%30%2E.01', 'http:///%3g%78%63%30%2e%30%32%35%30%2E.01'),
            array('http://', '192.168.0.1 hello', 'http:///192.168.0.1 hello'),
            array('http://', '192.168.0.257', 'http:///192.168.0.257'),
            array('http://', '[google.com]', 'http:///[google.com]'),
            array('http://', 'go\\@ogle.com', 'http:///go\\@ogle.com'),
            array('http://', 'go/@ogle.com', 'http:///go/@ogle.com'),
            array('http://', 'www.lookout.net::==80::==443::', 'www.lookout.net::==80::==443::'),
            array('http://', 'www.lookout.net::80::443', 'www.lookout.net::80::443'),
            array('http://', '\\\\', 'http:///\\\\'),
            array('http://', '\\\\\\/', 'http:///\\\\\\/'),
            array('http://', '\\\\./', 'http:///\\\\./'),
            array('http://', '//:@/', 'http://:@/'),
            array('http://', '\\google.com/foo', 'http:///\\google.com/foo'),
            array('http://', '\\\\google.com/foo', 'http:///\\\\google.com/foo'),
            array('http://', '//asdf@/', 'http://asdf@/'),
            array('http://', '//:81', 'http://:81'),
            array('http://', '://', 'http:///://'),
            array('http://', 'c:', 'c:'),
            array('http://', 'xxxx:', 'xxxx:'),
            array('http://', '.:.', '.:'),
            array('http://', '////@google.com/', 'http:////@google.com/'),
            array('http://', '@google.com', 'http:///@google.com'),
            array('http://', 'gOoGle.com', 'http:///gOoGle.com'),
            array('http://', '-foo.bar.com', 'http:///-foo.bar.com'),
            array('http://', 'foo-.bar.com', 'http:///foo-.bar.com'),
            array('http://', 'ab--cd.com', 'http:///ab--cd.com'),
            array('http://', 'xn--0.com', 'http:///xn--0.com'),
            array('http://', '.', 'http:///'),
            array('http://', '192.168.0.1', 'http:///192.168.0.1'),
            array('http://', '0300.0250.00.01', 'http:///0300.0250.00.01'),
            array('http://', '0xC0.0Xa8.0x0.0x1', 'http:///0xC0.0Xa8.0x0.0x1'),
            array('http://', '192.168.9.com', 'http:///192.168.9.com'),
            array('http://', '19a.168.0.1', 'http:///19a.168.0.1'),
            array('http://', '0308.0250.00.01', 'http:///0308.0250.00.01'),
            array('http://', '0xCG.0xA8.0x0.0x1', 'http:///0xCG.0xA8.0x0.0x1'),
            array('http://', '192', 'http:///192'),
            array('http://', '0xC0a80001', 'http:///0xC0a80001'),
            array('http://', '030052000001', 'http:///030052000001'),
            array('http://', '000030052000001', 'http:///000030052000001'),
            array('http://', '192.168', 'http:///192.168'),
            array('http://', '192.0x00A80001', 'http:///192.0x00A80001'),
            array('http://', '0xc0.052000001', 'http:///0xc0.052000001'),
            array('http://', '192.168.1', 'http:///192.168.1'),
            array('http://', '192.168.0.0.1', 'http:///192.168.0.0.1'),
            array('http://', '192.168.0.1.', 'http:///192.168.0.1.'),
            array('http://', '192.168.0.1. hello', 'http:///192.168.0.1. hello'),
            array('http://', '192.168.0.1..', 'http:///192.168.0.1..'),
            array('http://', '192.168..1', 'http:///192.168..1'),
            array('http://', '0x100.0', 'http:///0x100.0'),
            array('http://', '0x100.0.0', 'http:///0x100.0.0'),
            array('http://', '0x100.0.0.0', 'http:///0x100.0.0.0'),
            array('http://', '0.0x100.0.0', 'http:///0.0x100.0.0'),
            array('http://', '0.0.0x100.0', 'http:///0.0.0x100.0'),
            array('http://', '0.0.0.0x100', 'http:///0.0.0.0x100'),
            array('http://', '0.0.0x10000', 'http:///0.0.0x10000'),
            array('http://', '0.0x1000000', 'http:///0.0x1000000'),
            array('http://', '0x100000000', 'http:///0x100000000'),
            array('http://', '0xFF.0', 'http:///0xFF.0'),
            array('http://', '0xFF.0.0', 'http:///0xFF.0.0'),
            array('http://', '0xFF.0.0.0', 'http:///0xFF.0.0.0'),
            array('http://', '0.0xFF.0.0', 'http:///0.0xFF.0.0'),
            array('http://', '0.0.0xFF.0', 'http:///0.0.0xFF.0'),
            array('http://', '0.0.0.0xFF', 'http:///0.0.0.0xFF'),
            array('http://', '0.0.0xFFFF', 'http:///0.0.0xFFFF'),
            array('http://', '0.0xFFFFFF', 'http:///0.0xFFFFFF'),
            array('http://', '0xFFFFFFFF', 'http:///0xFFFFFFFF'),
            array('http://', '276.256.0xf1a2.077777', 'http:///276.256.0xf1a2.077777'),
            array('http://', '192.168.0.257', 'http:///192.168.0.257'),
            array('http://', '192.168.0xa20001', 'http:///192.168.0xa20001'),
            array('http://', '192.015052000001', 'http:///192.015052000001'),
            array('http://', '0X12C0a80001', 'http:///0X12C0a80001'),
            array('http://', '276.1.2', 'http:///276.1.2'),
            array('http://', '192.168.0.1 hello', 'http:///192.168.0.1 hello'),
            array('http://', '0000000000000300.0x00000000000000fF.00000000000000001', 'http:///0000000000000300.0x00000000000000fF.00000000000000001'),
            array('http://', '0000000000000300.0xffffffffFFFFFFFF.3022415481470977', 'http:///0000000000000300.0xffffffffFFFFFFFF.3022415481470977'),
            array('http://', '00000000000000000001', 'http:///00000000000000000001'),
            array('http://', '0000000000000000100000000000000001', 'http:///0000000000000000100000000000000001'),
            array('http://', '0.0.0.000000000000000000z', 'http:///0.0.0.000000000000000000z'),
            array('http://', '0.0.0.100000000000000000z', 'http:///0.0.0.100000000000000000z'),
            array('http://', '0.00.0x.0x0', 'http:///0.00.0x.0x0'),
            array('http://', '[', 'http:///['),
            array('http://', '[:', '[:'),
            array('http://', ']', 'http:///]'),
            array('http://', ':]', 'http:///:]'),
            array('http://', '[]', 'http:///[]'),
            array('http://', '[:]', '[:]'),
            array('http://', '2001:db8::1', '2001:db8::1'),
            array('http://', '[2001:db8::1', '[2001:db8::1'),
            array('http://', '2001:db8::1]', '2001:db8::1]'),
            array('http://', '[::]', '[::]'),
            array('http://', '[::1]', '[::1]'),
            array('http://', '[1::]', '[1::]'),
            array('http://', '[::192.168.0.1]', '[::192.168.0.1]'),
            array('http://', '[::ffff:192.168.0.1]', '[::ffff:192.168.0.1]'),
            array('http://', '[000:01:02:003:004:5:6:007]', '[000:01:02:003:004:5:6:007]'),
            array('http://', '[A:b:c:DE:fF:0:1:aC]', '[A:b:c:DE:fF:0:1:aC]'),
            array('http://', '[1:0:0:2::3:0]', '[1:0:0:2::3:0]'),
            array('http://', '[1::2:0:0:3:0]', '[1::2:0:0:3:0]'),
            array('http://', '[::eeee:192.168.0.1]', '[::eeee:192.168.0.1]'),
            array('http://', '[2001::192.168.0.1]', '[2001::192.168.0.1]'),
            array('http://', '[1:2:192.168.0.1:5:6]', '[1:2:192.168.0.1:5:6]'),
            array('http://', '[::ffff:192.1.2]', '[::ffff:192.1.2]'),
            array('http://', '[::ffff:0xC0.0Xa8.0x0.0x1]', '[::ffff:0xC0.0Xa8.0x0.0x1]'),
            array('http://', '[0:0::0:0:8]', '[0:0::0:0:8]'),
            array('http://', '[2001:db8::1]', '[2001:db8::1]'),
            array('http://', '[2001::db8::1]', '[2001::db8::1]'),
            array('http://', '[2001:db8:::1]', '[2001:db8:::1]'),
            array('http://', '[:::]', '[:::]'),
            array('http://', '[2001::.com]', '[2001::.com]'),
            array('http://', '[::192.168.0.0.1]', '[::192.168.0.0.1]'),
            array('http://', '[::ffff:192.168.0.0.1]', '[::ffff:192.168.0.0.1]'),
            array('http://', '[1:2:3:4:5:6:7:8:9]', '[1:2:3:4:5:6:7:8:9]'),
            array('http://', '[0:0:0:0:0:0:0:192.168.0.1]', '[0:0:0:0:0:0:0:192.168.0.1]'),
            array('http://', '[1:2:3:4:5:6::192.168.0.1]', '[1:2:3:4:5:6::192.168.0.1]'),
            array('http://', '[1:2:3:4:5:6::8]', '[1:2:3:4:5:6::8]'),
            array('http://', '[1:2:3:4:5:6:7:8:]', '[1:2:3:4:5:6:7:8:]'),
            array('http://', '[1:2:3:4:5:6:192.168.0.1:]', '[1:2:3:4:5:6:192.168.0.1:]'),
            array('http://', '[-1:2:3:4:5:6:7:8]', '[-1:2:3:4:5:6:7:8]'),
            array('http://', '[1::%1]', '[1::%1]'),
            array('http://', '[1::%eth0]', '[1::%eth0]'),
            array('http://', '[1::%]', '[1::%]'),
            array('http://', '[%]', 'http:///[%]'),
            array('http://', '[::%:]', '[::%:]'),
            array('http://', '[:0:0::0:0:8]', '[:0:0::0:0:8]'),
            array('http://', '[0:0::0:0:8:]', '[0:0::0:0:8:]'),
            array('http://', '[:0:0::0:0:8:]', '[:0:0::0:0:8:]'),
            array('http://', '[::192.168..1]', '[::192.168..1]'),
            array('http://', '[::1 hello]', '[::1 hello]'),
            array('mailto:', 'addr1', 'mailto:addr1'),
            array('mailto:', 'addr1@foo.com', 'mailto:addr1@foo.com'),
            array('mailto:', 'addr1 \\t', 'mailto:addr1 \\t'),
            array('mailto:', 'addr1?to=jon', 'mailto:addr1?to=jon'),
            array('mailto:', 'addr1,addr2', 'mailto:addr1,addr2'),
            array('mailto:', 'addr1, addr2', 'mailto:addr1, addr2'),
            array('mailto:', 'addr1%2caddr2', 'mailto:addr1%2caddr2'),
            array('mailto:', 'addr1?', 'mailto:addr1?'),
            array('http://www.example.com', '/././foo', 'http://www.example.com/foo'),
            array('http://www.example.com', '/./.foo', 'http://www.example.com/.foo'),
            array('http://www.example.com', '/foo/.', 'http://www.example.com/foo/'),
            array('http://www.example.com', '/foo/./', 'http://www.example.com/foo/'),
            array('http://www.example.com', '/foo/bar/..', 'http://www.example.com/foo/'),
            array('http://www.example.com', '/foo/bar/../', 'http://www.example.com/foo/'),
            array('http://www.example.com', '/foo/..bar', 'http://www.example.com/foo/..bar'),
            array('http://www.example.com', '/foo/bar/../ton', 'http://www.example.com/foo/ton'),
            array('http://www.example.com', '/foo/bar/../ton/../../a', 'http://www.example.com/a'),
            array('http://www.example.com', '/foo/../../..', 'http://www.example.com/'),
            array('http://www.example.com', '/foo/../../../ton', 'http://www.example.com/ton'),
            array('http://www.example.com', '/foo/%2e', 'http://www.example.com/foo/%2e'),
            array('http://www.example.com', '/foo/%2e%2', 'http://www.example.com/foo/%2e%2'),
            array('http://www.example.com', '/foo/%2e./%2e%2e/.%2e/%2e.bar', 'http://www.example.com/foo/%2e./%2e%2e/.%2e/%2e.bar'),
            array('http://www.example.com', '////../..', 'http:///'),
            array('http://www.example.com', '/foo/bar//../..', 'http://www.example.com/foo/'),
            array('http://www.example.com', '/foo/bar//..', 'http://www.example.com/foo/bar/'),
            array('http://www.example.com', '/foo/bar/..', 'http://www.example.com/foo/'),
            array('http://www.example.com', '/foo', 'http://www.example.com/foo'),
            array('http://www.example.com', '/%20foo', 'http://www.example.com/%20foo'),
            array('http://www.example.com', '/foo%', 'http://www.example.com/foo%'),
            array('http://www.example.com', '/foo%2', 'http://www.example.com/foo%2'),
            array('http://www.example.com', '/foo%2zbar', 'http://www.example.com/foo%2zbar'),
            array('http://www.example.com', '/foo%41%7a', 'http://www.example.com/foo%41%7a'),
            array('http://www.example.com', '/foo%00%51', 'http://www.example.com/foo%00%51'),
            array('http://www.example.com', '/(%28:%3A%29)', 'http://www.example.com/(%28:%3A%29)'),
            array('http://www.example.com', '/%3A%3a%3C%3c', 'http://www.example.com/%3A%3a%3C%3c'),
            array('http://www.example.com', '/foo\\tbar', 'http://www.example.com/foo\\tbar'),
            array('http://www.example.com', '\\\\foo\\\\bar', 'http://www.example.com/\\\\foo\\\\bar'),
            array('http://www.example.com', '/%7Ffp3%3Eju%3Dduvgw%3Dd', 'http://www.example.com/%7Ffp3%3Eju%3Dduvgw%3Dd'),
            array('http://www.example.com', '/@asdf%40', 'http://www.example.com/@asdf%40'),
            array('http://www.example.com:', 'as df', 'http://www.example.com:/as df'),
            array('http://www.example.com:', '-2', 'http://www.example.com:/-2'),
            array('http://www.example.com:', '80', 'http://www.example.com:/80'),
            array('http://www.example.com:', '8080', 'http://www.example.com:/8080'),
            array('http://www.example.com:', '', 'http://www.example.com:'),
            array('http://www.example.com/?', 'foo=bar', 'http://www.example.com/foo=bar'),
            array('http://www.example.com/?', 'as?df', 'http://www.example.com/as?df'),
            array('http://www.example.com/?', '\\%02hello%7f bye', 'http://www.example.com/\\%02hello%7f bye'),
            array('http://www.example.com/?', '%40%41123', 'http://www.example.com/%40%41123'),
            array('http://www.example.com/?', 'q=&lt;asdf&gt;', 'http://www.example.com/q=&lt;asdf&gt;'),
            array('http://www.example.com/?', 'q=\\"asdf\\"', 'http://www.example.com/q=\\"asdf\\"'),
            array('http://host/a', '\\\\\\\\Another\\\\path', 'http://host/\\\\\\\\Another\\\\path'),
            array('http://host/a', '/c:\\\\foo', 'http://host/c:\\\\foo'),
            array('http://host/a', '//c:\\\\foo', 'http://c:\\\\foo'),
            array('file:///C:/foo', 'http://host/', 'http://host/'),
            array('file:///C:/foo', 'bar', 'file:///C:/bar'),
            array('file:///C:/foo', '../../../bar.html', 'file:///bar.html'),
            array('file:///C:/foo', '/../bar.html', 'file:///bar.html'),
            array('http://host/a', '\\\\\\\\another\\\\path', 'http://host/\\\\\\\\another\\\\path'),
            array('file:///C:/something', '//c:/foo', 'file://c:/foo'),
            array('file:///C:/something', '//localhost/c:/foo', 'file://localhost/c:/foo'),
            array('file:///C:/foo', 'c:', 'c:'),
            array('file:///C:/foo', 'c:/foo', 'c:/foo'),
            array('http://host/a', 'c:\\\\foo', 'c:\\\\foo'),
            array('file:///C:/foo', '/z:/bar', 'file:///z:/bar'),
            array('file:///C:/foo', '/bar', 'file:///bar'),
            array('file://localhost/C:/foo', '/bar', 'file://localhost/bar'),
            array('file:///C:/foo/com/', '/bar', 'file:///bar'),
            array('file:///C:/something', '//somehost/path', 'file://somehost/path'),
            array('file:///C:/something', '/\\\\//somehost/path', 'file:///\\\\//somehost/path'),
            array('http://host/a', 'http://another/', 'http://another/'),
            array('http://host/a', 'http:////another/', 'http:////another/'),
            array('http://foo/bar', '', 'http://foo/bar'),
            array('http://foo/bar#ref', '', 'http://foo/bar'),
            array('http://foo/bar#', '', 'http://foo/bar'),
            array('http://foo/bar', ' another ', 'http://foo/ another '),
            array('http://foo/bar', ' . ', 'http://foo/ . '),
            array('http://foo/bar', ' \\t', 'http://foo/ \\t'),
            array('http://host/a', 'http:path', 'http:path'),
            array('http://host/a/', 'http:path', 'http:path'),
            array('http://host/a', 'http:/path', 'http:/path'),
            array('http://host/a', 'HTTP:/path', 'HTTP:/path'),
            array('http://host/a', 'https:host2', 'https:host2'),
            array('http://host/a', 'htto:/host2', 'htto:/host2'),
            array('http://host/a', '/b/c/d', 'http://host/b/c/d'),
            array('http://host/a', '\\\\b\\\\c\\\\d', 'http://host/\\\\b\\\\c\\\\d'),
            array('http://host/a', '/b/../c', 'http://host/c'),
            array('http://host/a?b#c', '/b/../c', 'http://host/c'),
            array('http://host/a', '\\\\b/../c?x#y', 'http://host/c?x#y'),
            array('http://host/a?b#c', '/b/../c?x#y', 'http://host/c?x#y'),
            array('http://host/a', 'b', 'http://host/b'),
            array('http://host/a', 'bc/de', 'http://host/bc/de'),
            array('http://host/a/', 'bc/de?query#ref', 'http://host/a/bc/de?query#ref'),
            array('http://host/a/', '.', 'http://host/a/'),
            array('http://host/a/', '..', 'http://host/'),
            array('http://host/a/', './..', 'http://host/'),
            array('http://host/a/', '../.', 'http://host/'),
            array('http://host/a/', '././.', 'http://host/a/'),
            array('http://host/a?query#ref', '../../../foo', 'http://host/foo'),
            array('http://host/a', '?foo=bar', 'http://host/a?foo=bar'),
            array('http://host/a?x=y#z', '?', 'http://host/a?'),
            array('http://host/a?x=y#z', '?foo=bar#com', 'http://host/a?foo=bar#com'),
            array('http://host/a', '#ref', 'http://host/a#ref'),
            array('http://host/a#b', '#', 'http://host/a#'),
            array('http://host/a?foo=bar#hello', '#bye', 'http://host/a?foo=bar#bye'),
            array('data:foobar', 'baz.html', 'data:baz.html'),
            array('data:foobar', 'data:baz', 'data:baz'),
            array('data:foobar', 'data:/base', 'data:/base'),
            array('data:foobar', 'http://host/', 'http://host/'),
            array('data:foobar', 'http:host', 'http:host'),
            array('http://foo/bar', './asd:fgh', 'http://foo/asd:fgh'),
            array('http://foo/bar', ':foo', 'http://foo/:foo'),
            array('http://foo/bar', ' hello world', 'http://foo/ hello world'),
            array('data:asdf', ':foo', 'data::foo'),
            array('http://host/a', ';foo', 'http://host/;foo'),
            array('http://host/a;', ';foo', 'http://host/;foo'),
            array('http://host/a', ';/../bar', 'http://host/bar'),
            array('http://host/a', '//another', 'http://another'),
            array('http://host/a', '//another/path?query#ref', 'http://another/path?query#ref'),
            array('http://host/a', '///another/path', 'http:///another/path'),
            array('http://host/a', '//Another\\\\path', 'http://Another\\\\path'),
            array('http://host/a', '//', 'http://'),
            array('http://host/a', '\\\\/another/path', 'http://host/\\\\/another/path'),
            array('http://host/a', '/\\\\Another\\\\path', 'http://host/\\\\Another\\\\path'),
            array('data:text/plain,baseURL', 'http://user:pass@foo:21/bar;par?b#c', 'http://user:pass@foo:21/bar;par?b#c'),
            array('data:text/plain,baseURL', 'http:foo.com', 'http:foo.com'),
            array('data:text/plain,baseURL', ' foo.com ', 'data:text/ foo.com '),
            array('data:text/plain,baseURL', 'http://f:21/ b ? d # e ', 'http://f:21/ b ? d # e '),
            array('data:text/plain,baseURL', 'http://f:/c', 'http://f:/c'),
            array('data:text/plain,baseURL', 'http://f:0/c', 'http://f:0/c'),
            array('data:text/plain,baseURL', 'http://f:00000000000000/c', 'http://f:00000000000000/c'),
            array('data:text/plain,baseURL', 'http://f:00000000000000000000080/c', 'http://f:00000000000000000000080/c'),
            array('data:text/plain,baseURL', 'http://f:b/c', 'http://f:b/c'),
            array('data:text/plain,baseURL', 'http://f: /c', 'http://f: /c'),
            array('data:text/plain,baseURL', 'http://f:fifty-two/c', 'http://f:fifty-two/c'),
            array('data:text/plain,baseURL', 'http://f:999999/c', 'http://f:999999/c'),
            array('data:text/plain,baseURL', 'http://f: 21 / b ? d # e ', 'http://f: 21 / b ? d # e '),
            array('data:text/plain,baseURL', '', 'data:text/plain,baseURL'),
            array('data:text/plain,baseURL', ':foo.com/', 'data:text/:foo.com/'),
            array('data:text/plain,baseURL', ':foo.com\\\\', 'data:text/:foo.com\\\\'),
            array('data:text/plain,baseURL', ':', 'data:text/:'),
            array('data:text/plain,baseURL', ':a', 'data:text/:a'),
            array('data:text/plain,baseURL', ':/', 'data:text/:/'),
            array('data:text/plain,baseURL', ':\\\\', 'data:text/:\\\\'),
            array('data:text/plain,baseURL', ':#', 'data:text/:#'),
            array('data:text/plain,baseURL', '#', 'data:text/plain,baseURL#'),
            array('data:text/plain,baseURL', '#/', 'data:text/plain,baseURL#/'),
            array('data:text/plain,baseURL', '#\\\\', 'data:text/plain,baseURL#\\\\'),
            array('data:text/plain,baseURL', '#;?', 'data:text/plain,baseURL#;?'),
            array('data:text/plain,baseURL', '?', 'data:text/plain,baseURL?'),
            array('data:text/plain,baseURL', '/', 'data:/'),
            array('data:text/plain,baseURL', ':23', 'data:text/:23'),
            array('data:text/plain,baseURL', '/:23', 'data:/:23'),
            array('data:text/plain,baseURL', '//', 'data://'),
            array('data:text/plain,baseURL', '::', 'data:text/::'),
            array('data:text/plain,baseURL', '::23', 'data:text/::23'),
            array('data:text/plain,baseURL', 'foo://', 'foo://'),
            array('data:text/plain,baseURL', 'http://a:b@c:29/d', 'http://a:b@c:29/d'),
            array('data:text/plain,baseURL', 'http::@c:29', 'http::@c:29'),
            array('data:text/plain,baseURL', 'http://&amp;a:foo(b]c@d:2/', 'http://&amp;a:foo(b]c@d:2/'),
            array('data:text/plain,baseURL', 'http://::@c@d:2', 'http://::@c@d:2'),
            array('data:text/plain,baseURL', 'http://foo.com:b@d/', 'http://foo.com:b@d/'),
            array('data:text/plain,baseURL', 'http://foo.com/\\\\@', 'http://foo.com/\\\\@'),
            array('data:text/plain,baseURL', 'http:\\\\\\\\foo.com\\\\', 'http:\\\\\\\\foo.com\\\\'),
            array('data:text/plain,baseURL', 'http:\\\\\\\\a\\\\b:c\\\\d@foo.com\\\\', 'http:\\\\\\\\a\\\\b:c\\\\d@foo.com\\\\'),
            array('data:text/plain,baseURL', 'foo:/', 'foo:/'),
            array('data:text/plain,baseURL', 'foo:/bar.com/', 'foo:/bar.com/'),
            array('data:text/plain,baseURL', 'foo://///////', 'foo://///////'),
            array('data:text/plain,baseURL', 'foo://///////bar.com/', 'foo://///////bar.com/'),
            array('data:text/plain,baseURL', 'foo:////://///', 'foo:////://///'),
            array('data:text/plain,baseURL', 'c:/foo', 'c:/foo'),
            array('data:text/plain,baseURL', '//foo/bar', 'data://foo/bar'),
            array('data:text/plain,baseURL', 'http://foo/path;a??e#f#g', 'http://foo/path;a??e#f#g'),
            array('data:text/plain,baseURL', 'http://foo/abcd?efgh?ijkl', 'http://foo/abcd?efgh?ijkl'),
            array('data:text/plain,baseURL', 'http://foo/abcd#foo?bar', 'http://foo/abcd#foo?bar'),
            array('data:text/plain,baseURL', '[61:24:74]:98', '[61:24:74]:98'),
            array('data:text/plain,baseURL', 'http://[61:27]:98', 'http://[61:27]:98'),
            array('data:text/plain,baseURL', 'http:[61:27]/:foo', 'http:[61:27]/:foo'),
            array('data:text/plain,baseURL', 'http://[1::2]:3:4', 'http://[1::2]:3:4'),
            array('data:text/plain,baseURL', 'http://2001::1', 'http://2001::1'),
            array('data:text/plain,baseURL', 'http://[2001::1', 'http://[2001::1'),
            array('data:text/plain,baseURL', 'http://2001::1]', 'http://2001::1]'),
            array('data:text/plain,baseURL', 'http://2001::1]:80', 'http://2001::1]:80'),
            array('data:text/plain,baseURL', 'http://[2001::1]', 'http://[2001::1]'),
            array('data:text/plain,baseURL', 'http://[2001::1]:80', 'http://[2001::1]:80'),
            array('data:text/plain,baseURL', 'http://[[::]]', 'http://[[::]]'),
            array('http://www.example.com/foo/bar', 'http://user:pass@foo:21/bar;par?b#c', 'http://user:pass@foo:21/bar;par?b#c'),
            array('http://www.example.com/foo/bar', 'http:foo.com', 'http:foo.com'),
            array('http://www.example.com/foo/bar', ' foo.com ', 'http://www.example.com/foo/ foo.com '),
            array('http://www.example.com/foo/bar', 'http://f:21/ b ? d # e ', 'http://f:21/ b ? d # e '),
            array('http://www.example.com/foo/bar', 'http://f:/c', 'http://f:/c'),
            array('http://www.example.com/foo/bar', 'http://f:0/c', 'http://f:0/c'),
            array('http://www.example.com/foo/bar', 'http://f:00000000000000/c', 'http://f:00000000000000/c'),
            array('http://www.example.com/foo/bar', 'http://f:00000000000000000000080/c', 'http://f:00000000000000000000080/c'),
            array('http://www.example.com/foo/bar', 'http://f:b/c', 'http://f:b/c'),
            array('http://www.example.com/foo/bar', 'http://f: /c', 'http://f: /c'),
            array('http://www.example.com/foo/bar', 'http://f:fifty-two/c', 'http://f:fifty-two/c'),
            array('http://www.example.com/foo/bar', 'http://f:999999/c', 'http://f:999999/c'),
            array('http://www.example.com/foo/bar', 'http://f: 21 / b ? d # e ', 'http://f: 21 / b ? d # e '),
            array('http://www.example.com/foo/bar', '', 'http://www.example.com/foo/bar'),
            array('http://www.example.com/foo/bar', ':foo.com/', 'http://www.example.com/foo/:foo.com/'),
            array('http://www.example.com/foo/bar', ':foo.com\\\\', 'http://www.example.com/foo/:foo.com\\\\'),
            array('http://www.example.com/foo/bar', ':', 'http://www.example.com/foo/:'),
            array('http://www.example.com/foo/bar', ':a', 'http://www.example.com/foo/:a'),
            array('http://www.example.com/foo/bar', ':/', 'http://www.example.com/foo/:/'),
            array('http://www.example.com/foo/bar', ':\\\\', 'http://www.example.com/foo/:\\\\'),
            array('http://www.example.com/foo/bar', ':#', 'http://www.example.com/foo/:#'),
            array('http://www.example.com/foo/bar', '#', 'http://www.example.com/foo/bar#'),
            array('http://www.example.com/foo/bar', '#/', 'http://www.example.com/foo/bar#/'),
            array('http://www.example.com/foo/bar', '#\\\\', 'http://www.example.com/foo/bar#\\\\'),
            array('http://www.example.com/foo/bar', '#;?', 'http://www.example.com/foo/bar#;?'),
            array('http://www.example.com/foo/bar', '?', 'http://www.example.com/foo/bar?'),
            array('http://www.example.com/foo/bar', '/', 'http://www.example.com/'),
            array('http://www.example.com/foo/bar', ':23', 'http://www.example.com/foo/:23'),
            array('http://www.example.com/foo/bar', '/:23', 'http://www.example.com/:23'),
            array('http://www.example.com/foo/bar', '//', 'http://'),
            array('http://www.example.com/foo/bar', '::', 'http://www.example.com/foo/::'),
            array('http://www.example.com/foo/bar', '::23', 'http://www.example.com/foo/::23'),
            array('http://www.example.com/foo/bar', 'foo://', 'foo://'),
            array('http://www.example.com/foo/bar', 'http://a:b@c:29/d', 'http://a:b@c:29/d'),
            array('http://www.example.com/foo/bar', 'http::@c:29', 'http::@c:29'),
            array('http://www.example.com/foo/bar', 'http://&amp;a:foo(b]c@d:2/', 'http://&amp;a:foo(b]c@d:2/'),
            array('http://www.example.com/foo/bar', 'http://::@c@d:2', 'http://::@c@d:2'),
            array('http://www.example.com/foo/bar', 'http://foo.com:b@d/', 'http://foo.com:b@d/'),
            array('http://www.example.com/foo/bar', 'http://foo.com/\\\\@', 'http://foo.com/\\\\@'),
            array('http://www.example.com/foo/bar', 'http:\\\\\\\\foo.com\\\\', 'http:\\\\\\\\foo.com\\\\'),
            array('http://www.example.com/foo/bar', 'http:\\\\\\\\a\\\\b:c\\\\d@foo.com\\\\', 'http:\\\\\\\\a\\\\b:c\\\\d@foo.com\\\\'),
            array('http://www.example.com/foo/bar', 'foo:/', 'foo:/'),
            array('http://www.example.com/foo/bar', 'foo:/bar.com/', 'foo:/bar.com/'),
            array('http://www.example.com/foo/bar', 'foo://///////', 'foo://///////'),
            array('http://www.example.com/foo/bar', 'foo://///////bar.com/', 'foo://///////bar.com/'),
            array('http://www.example.com/foo/bar', 'foo:////://///', 'foo:////://///'),
            array('http://www.example.com/foo/bar', 'c:/foo', 'c:/foo'),
            array('http://www.example.com/foo/bar', '//foo/bar', 'http://foo/bar'),
            array('http://www.example.com/foo/bar', 'http://foo/path;a??e#f#g', 'http://foo/path;a??e#f#g'),
            array('http://www.example.com/foo/bar', 'http://foo/abcd?efgh?ijkl', 'http://foo/abcd?efgh?ijkl'),
            array('http://www.example.com/foo/bar', 'http://foo/abcd#foo?bar', 'http://foo/abcd#foo?bar'),
            array('http://www.example.com/foo/bar', '[61:24:74]:98', '[61:24:74]:98'),
            array('http://www.example.com/foo/bar', 'http://[61:27]:98', 'http://[61:27]:98'),
            array('http://www.example.com/foo/bar', 'http:[61:27]/:foo', 'http:[61:27]/:foo'),
            array('http://www.example.com/foo/bar', 'http://[1::2]:3:4', 'http://[1::2]:3:4'),
            array('http://www.example.com/foo/bar', 'http://2001::1', 'http://2001::1'),
            array('http://www.example.com/foo/bar', 'http://[2001::1', 'http://[2001::1'),
            array('http://www.example.com/foo/bar', 'http://2001::1]', 'http://2001::1]'),
            array('http://www.example.com/foo/bar', 'http://2001::1]:80', 'http://2001::1]:80'),
            array('http://www.example.com/foo/bar', 'http://[2001::1]', 'http://[2001::1]'),
            array('http://www.example.com/foo/bar', 'http://[2001::1]:80', 'http://[2001::1]:80'),
            array('http://www.example.com/foo/bar', 'http://[[::]]', 'http://[[::]]'),
            array('http://example.org/foo/bar', 'http://example.com/', 'http://example.com/'),
            array('http://example.org/foo/bar', 'http://example.com/', 'http://example.com/'),
            array('http://example.org/foo/bar', '/', 'http://example.org/'),
            array('http://iris.test.ing/', '?value= foo bar', 'http://iris.test.ing/?value= foo bar'),
            array('http://user%40', 'example.com', 'http://user%40/example.com'),
            array('http://user%3Ainfo%40', 'example.com', 'http://user%3Ainfo%40/example.com'),
            array('http://user@', 'example.com', 'http://user@/example.com'),
            array('http://user:info@', 'example.com', 'http://user:info@/example.com')
        );
    }

    /**
     * Test conversion to relative IRI reference
     *
     * @param string $iri            The reference IRI to convert to a
     *                               relative reference.
     * @param string $base           The base IRI.
     * @param bool   $schemaRelative Should schema-relative IRIs be created?
     * @param string $expected       The expected IRI reference.
     *
     * @dataProvider relativizeProvider
     */
    public function testRelativize($iri, $base, $schemaRelative, $expected)
    {
        $iri = new IRI($iri);
        $this->assertEquals($expected, (string)$iri->relativeTo($base, $schemaRelative));

        $base = new IRI($base);
        $this->assertEquals($expected, (string)$base->baseFor((string)$iri, $schemaRelative));
    }

    /**
     * Conversion to relative IRI reference test cases
     *
     * These test cases are adaptations of the tests in
     * {@link http://dig.csail.mit.edu/2005/ajar/ajaw/test/uri-test-doc.html}.
     *
     * @return array The test cases.
     */
    public function relativizeProvider()
    {
        return array(  // $iri, $base, $schemaRelative, $expected
            array('http://ex/x/y/z', 'http://ex/x/y/z', false, 'z'),
            array('https://example.com/x/y/z', 'http://example.com/x/y/z', false, 'https://example.com/x/y/z'),
            array('http://example.com/x/y/z/', 'http://example.com/x/y/z/', false, './'),
            array('http://example.com/x/y/z/?query', 'http://example.com/x/y/z/', false, '?query'),
            array('http://example.com/x/y/z/#fragment', 'http://example.com/x/y/z/', false, '#fragment'),
            array('http://example.com/x/y/z', 'http://example.com/x/y/z', false, 'z'),
            array('http://example.com/x/y/z?query', 'http://example.com/x/y/z', false, '?query'),
            array('http://example.com/x/y/z#fragment', 'http://example.com/x/y/z', false, '#fragment'),
            array('http://example.com/x/y/z:a', 'http://example.com/x/y/', false, './z:a'),
            array('http://example.com/x/y/z', 'http://example.com/x/y', false, 'y/z'),
            array('http://example.com/x/y/z', 'http://example.com/a/b/c', false, '../../x/y/z'),
            array('http://example.com/x/y/z?query', 'http://example.com/a/b/c', false, '../../x/y/z?query'),
            array('http://example.com/x/y/z#fragment', 'http://example.com/a/b/c', false, '../../x/y/z#fragment'),
            array('http://example.com/x/y/z?query#fragment', 'http://example.com/a/b/c', false, '../../x/y/z?query#fragment'),
            array('http://example.org/x/y/z', 'http://example.com/a/b/c', false, 'http://example.org/x/y/z'),
            array('http://example.org/x/y/z', 'http://example.com/a/b/c', true, '//example.org/x/y/z'),
            array('http://example/x/abc', 'http://example/x/y/z', false, '../abc'),
            array('file:/ex/x/q/r#s', 'file:/ex/x/y', false, 'q/r#s'),
            array('http://ex/x/y', null, false, 'http://ex/x/y'),
            array('http://example.com/a', 'http://example.com/a/', false, '../a'),
            array('http://example.com/a', 'http://example.com/a/b', false, '../a'),
            array('http://example.com/a/b/c?query', 'http://example.com/a/b/c?query', false, '?query'),
            array('http://ex/r', 'http://ex/x/y/z', false, '../../r'),
            // http://dig.csail.mit.edu/2005/ajar/ajaw/test/uri-test-doc.html
            array('bar:abc', 'foo:xyz', false, 'bar:abc'),
            array('http://example/x/abc', 'http://example/x/y/z', false, '../abc'),
            array('http://example/x/abc', 'http://example2/x/y/z', false, 'http://example/x/abc'),
            array('http://example/x/abc', 'http://example2/x/y/z', true, '//example/x/abc'),
            array('http://ex/x/r', 'http://ex/x/y/z', false, '../r'),
            array('http://ex/x/q/r', 'http://ex/x/y', false, 'q/r'),
            array('http://ex/x/q/r#s', 'http://ex/x/y', false, 'q/r#s'),
            array('http://ex/x/q/r#s/t', 'http://ex/x/y', false, 'q/r#s/t'),
            array('ftp://ex/x/q/r', 'http://ex/x/y', false, 'ftp://ex/x/q/r'),
            array('http://ex/x/y/z/', 'http://ex/x/y', false, 'y/z/'),
            array('file:/swap/test/animal.rdf#Animal', 'file:/swap/test/animal.rdf', false, '#Animal'),
            array('file:/e/x/abc', 'file:/e/x/y/z', false, '../abc'),
            array('file:/example/x/abc', 'file:/example2/x/y/z', false, '../../../example/x/abc'),
            array('file:/ex/x/r', 'file:/ex/x/y/z', false, '../r'),
            array('file:/r', 'file:/ex/x/y/z', false, '../../../r'),
            array('file:/ex/x/q/r', 'file:/ex/x/y/z', false, '../q/r'),
            array('file:/ex/x/q/r#s', 'file:/ex/x/y', false, 'q/r#s'),
            array('file:/ex/x/q/r#', 'file:/ex/x/y', false, 'q/r#'),
            array('file:/ex/x/q/r#s/t', 'file:/ex/x/y', false, 'q/r#s/t'),
            array('ftp://ex/x/q/r', 'file:/ex/x/y', false, 'ftp://ex/x/q/r'),
            array('file:/ex/x/y/z/', 'file:/ex/x/y/', false, 'z/'),
            array('file://meetings.example.com/cal#m1', 'file:/devel/WWW/2000/10/swap/test/reluri-1.n3', false, 'file://meetings.example.com/cal#m1'),
            array('file://meetings.example.com/cal#m1', 'file:/devel/WWW/2000/10/swap/test/reluri-1.n3', true, '//meetings.example.com/cal#m1'),
            array('file://meetings.example.com/cal#m1', 'file:/home/connolly/w3ccvs/WWW/2000/10/swap/test/reluri-1.n3', false, 'file://meetings.example.com/cal#m1'),
            array('file://meetings.example.com/cal#m1', 'file:/home/connolly/w3ccvs/WWW/2000/10/swap/test/reluri-1.n3', true, '//meetings.example.com/cal#m1'),
            array('file:/some/dir/#blort', 'file:/some/dir/foo', false, './#blort'),
            array('file:/some/dir/#', 'file:/some/dir/foo', false, './#'),
            array('http://example/x/abc', 'http://example/x/y%2Fz', false, 'abc'),
            array('http://example/x%2Fabc', 'http://example/x/y/z', false, '../../x%2Fabc'),
            array('http://example/x%2Fabc', 'http://example/x/y%2Fz', false, '../x%2Fabc'),
            array('http://example/x%2Fy/abc', 'http://example/x%2Fy/z', false, 'abc'),
            array('http://example/x/', 'http://example/x/abc.efg', false, './'),   // ???
            array('http://www.w3.org/2002/01/tr-automation/tr.rdf', 'http://www.w3.org/People/Berners-Lee/card.rdf', false, '../../2002/01/tr-automation/tr.rdf'),
        );
    }

    /**
     * Invalid IRI test cases
     *
     * These test cases were taken from
     * {@link http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html}.
     *
     * @return array The invalid IRI test cases.
     */
    public function invalidIriProvider()
    {
        return array(
            array('[2010:836B:4179::836B:4179]'),
            array('http://foo.org:80Path/More'),
            array('::'),
            array(' '),  // is this an invalid IRI??
            array('%'),
            array('A%Z'),
            array('%ZZ'),
            array('%AZ'),
            array('A C'),
            array('A\C"'),
            array('A`C'),
            array('A<C'),
            array('A>C'),
            array('A^C'),
            array('A\\C'),
            array('A{C'),
            array('A|C'),
            array('A}C'),
            array('A[C'),
            array('A]C'),
            array('A[**]C'),
            array('http://[xyz]/'),
            array('http://]/'),
            array('http://example.org/[2010:836B:4179::836B:4179]'),
            array('http://example.org/abc#[2010:836B:4179::836B:4179]'),
            array('http://example.org/xxx/[qwerty]#a[b]'),
            array('http://example.org/xxx/qwerty#a#b'),
            array('http://user:pass@example.org:99aaa/bbb')
        );
    }

    /**
     * Normalization test cases
     *
     * These test cases were taken from
     * {@link http://www.ninebynine.org/Software/HaskellUtils/Network/URITestDescriptions.html}.
     *
     * @return array The normalization test cases.
     */
    public function normalizationProvider()
    {
        return array(
            // Case normalization; cf. RFC3986 section 6.2.2.1 (NOTE: authority case normalization is not performed)
            array('HTTP://EXAMPLE.com/Root/%2a?%2b#%2c', 'http://EXAMPLE.com/Root/%2A?%2B#%2C'),
            // Encoding normalization; cf. RFC3986 section 6.2.2.2
            array('HTTP://EXAMPLE.com/Root/%7eMe/', 'HTTP://EXAMPLE.com/Root/~Me/'),
            array('foo:%40%41%5a%5b%60%61%7a%7b%2f%30%39%3a%2d%2e%5f%7e', 'foo:%40AZ%5b%60az%7b%2f09%3a-._~'),
            array('foo:%3a%2f%3f%23%5b%5d%40', 'foo:%3a%2f%3f%23%5b%5d%40'),
            // Path segment normalization; cf. RFC3986 section 6.2.2.3
            array('http://example/a/b/../../c', 'http://example/c'),
            array('http://example/a/b/c/../../', 'http://example/a/'),
            array('http://example/a/b/c/./', 'http://example/a/b/c/'),
            array('http://example/a/b/c/.././', 'http://example/a/b/'),
            array('http://example/a/b/c/d/../../../../e', 'http://example/e'),
            array('http://example/a/b/c/d/../.././../../e', 'http://example/e'),
            array('http://example/a/b/../.././../../e', 'http://example/e'),
            array('foo:a/b/../.././../../e', 'foo:e')
        );
    }
}
