<?php
/**
 * 模版引擎配置
 * @author shonenada
 *
 */ 
return array(
    // 模版引擎配置
    'config' => array(
        'environment' => array(
            // 开启调试模式，生产环境中请关闭调试模式。
            'debug' => true,
            // 缓存的目录
            'cache' => APPROOT. 'cache',
            'auto_reload' => true,      // 检测模版修改自动重编译
            'optimizations' => -1,      // 优化系数
        ),
    )
);
