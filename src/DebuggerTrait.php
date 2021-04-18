<?php
/**
 * Debugging utilities
 * 
 * @package W6\Debugger
 */


namespace W6\Debugger;

trait DebuggerTrait 
{

    protected function applyFunction( $function, $vars )
    {
        ob_start();
        debug_print_backtrace();
        $trace = ob_get_contents();
        ob_end_clean();
        $trace = join(
            PHP_EOL, 
            array_slice(
                explode(PHP_EOL, $trace), 
                2
            )
        );
        $trace = preg_replace_callback ('/^#(\d+)/m', [ $this, 'renumber' ], $trace);
        echo '<pre class="backtrace"><code>'.PHP_EOL;
        echo $trace;
        echo '</code></pre>'.PHP_EOL;
        echo '<pre class="debug"><code>'.PHP_EOL;
        array_walk($vars, $function);
        echo '</code></pre>'.PHP_EOL;

    }

    public function renumber($i)
    {
        return '#' . ( $i[1] - 2 ) . ' -> ';
    }

    public function pr()
    {
        $this->applyFunction( 'print_r', func_get_args() );
    }

    public function prd()
    {
        call_user_func_array( [self, 'pr'], func_get_args() );
        die();
    }

    public function vd()
    {
        $this->applyFunction( 'var_dump', func_get_args() );
    }

    public function vdd()
    {
        call_user_func_array( [self, 'vd'], func_get_args() );
        die();
    }

    public function ve()
    {
        $this->applyFunction( 'var_export', func_get_args() );
    }

    public function ved()
    {
        call_user_func_array( [self, 've'], func_get_args() );
        die();
    }

}
