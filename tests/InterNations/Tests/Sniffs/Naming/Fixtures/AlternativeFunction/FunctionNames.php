<?php
class FunctionNames
{
    public function join()
    {
        $newString = join(',', ['foo', 'bar']);

        $pn = new PaamayimNekudotayim();
        $newString = $pn->join('a', 'b');
        $newString = $pn
            ->join('a', 'b');

        $newString = $pn->
            join('a', 'b'); join(',', ['foo', 'bar']);join(',', ['foo', 'bar']);
    }

    public function sizeof()
    {
        $count = sizeof(['foo', 'bar']);
    }

    private function fputs()
    {
        $fp = tmpfile();
        fputs($fp, '');
        fclose($fp);
    }

    public function chop()
    {
        chop('string');
    }

    public function is_real()
    {
        $float = 123;
    }

    protected function strchr()
    {
        strchr('This is a string', 'is');
    }

    private function doubleval()
    {
        doubleval(123);
    }

    public function key_exists()
    {
        if (key_exists('key', ['foo', 'bar'])) {
        }
    }

    protected function is_double()
    {
        $double = 123.45;
        if (is_double($double)) {
        } else {
        }
    }

    public function ini_alter()
    {
        ini_alter('display_errors', 1);
    }

    public function is_long()
    {
        is_long(123);
    }

    public function is_integer()
    {
        is_integer(123);
    }

    public function is_real()
    {
        is_real(123);
    }

    public function pos()
    {
        pos(123);
        func(pos(123));
        new Something(pos(123));
    }

    public function sha1()
    {
        sha1('foo');
    }

    public function sha1_file()
    {
        sha1_file('foo');
    }

    public function md5()
    {
        md5('foo');
    }

    public function md5_file()
    {
        md5_file('foo');
    }

    public function var_dump()
    {
        var_dump("something");
    }

    public function print_r()
    {
        print_r("something");
    }

    public function printf()
    {
        printf("something");
    }

    public function vprintf()
    {
        vprintf("something", []);
    }

    public function bla()
    {
        echo "something";
        print "something";
    }

    protected function tPaamayimNekudotayim()
    {
        PaamayimNekudotayim::sizeof([1, 2, 3]);
    }
}

class PaamayimNekudotayim
{
    public static function sizeof($var)
    {
        return count($var);
    }

    public function join($a, $b)
    {
        return $a . $b;
    }
}

uniqid();
openssl_random_pseudo_bytes();
srand();
rand();
eval('echo 1');
