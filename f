PHPUnit 4.8.24 by Sebastian Bergmann and contributors.

.AuTest::test_control
.AuTest::test_nologin
.check all funtion  ...
 PHP Parse error:  syntax error, unexpected ']' in /home/jim/admin.yb1v1.com/app/Config/menu.php on line 421
PHP Stack trace:
PHP   1. {main}() /usr/bin/phpunit:0
PHP   2. PHPUnit_TextUI_Command::main() /usr/bin/phpunit:46
PHP   3. PHPUnit_TextUI_Command->run() /usr/share/php/PHPUnit/TextUI/Command.php:129
PHP   4. PHPUnit_TextUI_TestRunner->doRun() /usr/share/php/PHPUnit/TextUI/Command.php:176
PHP   5. PHPUnit_Framework_TestSuite->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/TextUI/TestRunner.php:440
PHP   6. PHPUnit_Framework_TestSuite->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestSuite.php:747
PHP   7. PHPUnit_Framework_TestCase->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestSuite.php:747
PHP   8. PHPUnit_Framework_TestResult->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:724
PHP   9. PHPUnit_Framework_TestCase->runBare() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestResult.php:612
PHP  10. PHPUnit_Framework_TestCase->runTest() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:768
PHP  11. ReflectionMethod->invokeArgs() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:909
PHP  12. AuTest->test_url_1() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:909
PHP  13. AuTest->login() /home/jim/admin.yb1v1.com/tests/AuTest.php:48
PHP  14. Illuminate\Foundation\Testing\TestCase->json() /home/jim/admin.yb1v1.com/tests/AuTest.php:21
PHP  15. Illuminate\Foundation\Testing\TestCase->call() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Testing/Concerns/MakesHttpRequests.php:72
PHP  16. Illuminate\Foundation\Http\Kernel->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Testing/Concerns/MakesHttpRequests.php:506
PHP  17. Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:99
PHP  18. Illuminate\Pipeline\Pipeline->then() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:132
PHP  19. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  20. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  21. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  22. Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  23. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  24. Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  25. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/CheckForMaintenanceMode.php:44
PHP  26. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  27. Illuminate\Foundation\Http\Kernel->Illuminate\Foundation\Http\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  28. Illuminate\Routing\Router->dispatch() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:246
PHP  29. Illuminate\Routing\Router->dispatchToRoute() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:675
PHP  30. Illuminate\Routing\Router->runRouteWithinStack() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:699
PHP  31. Illuminate\Pipeline\Pipeline->then() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:726
PHP  32. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  33. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  34. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  35. Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  36. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  37. Illuminate\Session\Middleware\StartSession->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  38. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:62
PHP  39. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  40. Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  41. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  42. App\Http\Middleware\MyMiddleware->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  43. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/app/Http/Middleware/MyMiddleware.php:83
PHP  44. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  45. Illuminate\Routing\Router->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  46. Illuminate\Routing\Route->run() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:724
PHP  47. Illuminate\Routing\Route->runController() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Route.php:140
PHP  48. Illuminate\Routing\ControllerDispatcher->dispatch() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Route.php:174
PHP  49. Illuminate\Routing\ControllerDispatcher->callWithinStack() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:54
PHP  50. Illuminate\Pipeline\Pipeline->then() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:96
PHP  51. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  52. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  53. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  54. Illuminate\Routing\ControllerDispatcher->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  55. Illuminate\Routing\ControllerDispatcher->call() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:94
PHP  56. Illuminate\Routing\Controller->callAction() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:146
PHP  57. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Controller.php:80
PHP  58. App\Http\Controllers\index->publish() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Controller.php:80
PHP  59. App\Http\Controllers\login->login() /home/jim/admin.yb1v1.com/app/Http/Controllers/index.php:307
PHP  60. App\Http\Controllers\login->reset_power() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:325
PHP  61. App\Helper\Config::get_menu() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:256
PHP  62. spl_autoload_call() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:38
PHP  63. Composer\Autoload\ClassLoader->loadClass() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:0
PHP  64. Composer\Autoload\includeFile() /home/jim/admin.yb1v1.com/vendor/composer/ClassLoader.php:301
PHP Fatal error:  Uncaught exception 'Illuminate\Contracts\Container\BindingResolutionException' with message 'Target [Illuminate\Contracts\Debug\ExceptionHandler] is not instantiable.' in /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Container/Container.php:752
Stack trace:
#0 /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Container/Container.php(633): Illuminate\Container\Container->build('Illuminate\Cont...', Array)
#1 /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(697): Illuminate\Container\Container->make('Illuminate\Cont...', Array)
#2 /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php(155): Illuminate\Foundation\Application->make('Illuminate\Cont...')
#3 /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php(79): Illuminate\Foundation\Bootstrap\HandleExceptions->getExceptionHandler()
#4 /home/jim/admin.yb1v1.com/vendor/laravel/f in /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Container/Container.php on line 752
PHP Stack trace:
PHP   1. {main}() /usr/bin/phpunit:0
PHP   2. PHPUnit_TextUI_Command::main() /usr/bin/phpunit:46
PHP   3. PHPUnit_TextUI_Command->run() /usr/share/php/PHPUnit/TextUI/Command.php:129
PHP   4. PHPUnit_TextUI_TestRunner->doRun() /usr/share/php/PHPUnit/TextUI/Command.php:176
PHP   5. PHPUnit_Framework_TestSuite->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/TextUI/TestRunner.php:440
PHP   6. PHPUnit_Framework_TestSuite->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestSuite.php:747
PHP   7. PHPUnit_Framework_TestCase->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestSuite.php:747
PHP   8. PHPUnit_Framework_TestResult->run() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:724
PHP   9. PHPUnit_Framework_TestCase->runBare() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestResult.php:612
PHP  10. PHPUnit_Framework_TestCase->runTest() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:768
PHP  11. ReflectionMethod->invokeArgs() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:909
PHP  12. AuTest->test_url_1() /home/jim/admin.yb1v1.com/vendor/phpunit/phpunit/src/Framework/TestCase.php:909
PHP  13. AuTest->login() /home/jim/admin.yb1v1.com/tests/AuTest.php:48
PHP  14. Illuminate\Foundation\Testing\TestCase->json() /home/jim/admin.yb1v1.com/tests/AuTest.php:21
PHP  15. Illuminate\Foundation\Testing\TestCase->call() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Testing/Concerns/MakesHttpRequests.php:72
PHP  16. Illuminate\Foundation\Http\Kernel->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Testing/Concerns/MakesHttpRequests.php:506
PHP  17. Illuminate\Foundation\Http\Kernel->sendRequestThroughRouter() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:99
PHP  18. Illuminate\Pipeline\Pipeline->then() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:132
PHP  19. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  20. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  21. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  22. Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  23. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  24. Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  25. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/CheckForMaintenanceMode.php:44
PHP  26. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  27. Illuminate\Foundation\Http\Kernel->Illuminate\Foundation\Http\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  28. Illuminate\Routing\Router->dispatch() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php:246
PHP  29. Illuminate\Routing\Router->dispatchToRoute() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:675
PHP  30. Illuminate\Routing\Router->runRouteWithinStack() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:699
PHP  31. Illuminate\Pipeline\Pipeline->then() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:726
PHP  32. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  33. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  34. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  35. Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  36. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  37. Illuminate\Session\Middleware\StartSession->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  38. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php:62
PHP  39. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  40. Illuminate\Pipeline\Pipeline->Illuminate\Pipeline\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:32
PHP  41. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  42. App\Http\Middleware\MyMiddleware->handle() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:124
PHP  43. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/app/Http/Middleware/MyMiddleware.php:83
PHP  44. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  45. Illuminate\Routing\Router->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  46. Illuminate\Routing\Route->run() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Router.php:724
PHP  47. Illuminate\Routing\Route->runController() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Route.php:140
PHP  48. Illuminate\Routing\ControllerDispatcher->dispatch() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Route.php:174
PHP  49. Illuminate\Routing\ControllerDispatcher->callWithinStack() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:54
PHP  50. Illuminate\Pipeline\Pipeline->then() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:96
PHP  51. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  52. Illuminate\Routing\Pipeline->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php:103
PHP  53. call_user_func() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  54. Illuminate\Routing\ControllerDispatcher->Illuminate\Routing\{closure}() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Pipeline.php:52
PHP  55. Illuminate\Routing\ControllerDispatcher->call() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:94
PHP  56. Illuminate\Routing\Controller->callAction() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php:146
PHP  57. call_user_func_array() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Controller.php:80
PHP  58. App\Http\Controllers\index->publish() /home/jim/admin.yb1v1.com/vendor/laravel/framework/src/Illuminate/Routing/Controller.php:80
PHP  59. App\Http\Controllers\login->login() /home/jim/admin.yb1v1.com/app/Http/Controllers/index.php:307
PHP  60. App\Http\Controllers\login->reset_power() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:325
PHP  61. App\Helper\Config::get_menu() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:256
PHP  62. spl_autoload_call() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:38
PHP  63. Composer\Autoload\ClassLoader->loadClass() /home/jim/admin.yb1v1.com/app/Http/Controllers/login.php:0
PHP  64. Composer\Autoload\includeFile() /home/jim/admin.yb1v1.com/vendor/composer/ClassLoader.php:301
AuTest::test_url_1
test login
TEST ERROR. end
