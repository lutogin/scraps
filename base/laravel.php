<ul class="collapsible popout" data-collapsible="accordion">

	<li>
		<div class="collapsible-header">Установка и базовые комманды</div>
		<div class="collapsible-body">
			<p>В папке с проэктами:</p>
			<pre data-enlighter-language="php">
composer global require "laravel/installer"

composer create-project --prefer-dist laravel/laravel PROJECTname
			</pre>
			<p>Далее настраиваем <b>config/app</b></p>
			<p>И создаем контроллеры, контроллеры находятся в <b>app/http/controllers</b></p>
			<pre data-enlighter-language="php">
php artisan make:controller NameController

//вызов справки
php artisan make:controller --help
			</pre>
        </div>
    </li>

    <li>
        <div class="collapsible-header">Роутинг</div>
        <div class="collapsible-body">
			<a class="link" href="https://laravel.com/docs/5.6/routing">Офф докуменитация</a>
			<p>Роутинг находится в /routes/web.php</p>
			<pre data-enlighter-language="php">
//посмотреть все правила роутинга
php artisan route::list

Route::post('путь', function () {
    return view('имя_запускаемого_шаблона');
});

Route::get('/', function () {
    return view('welcome');
});

//для маршрутизации всех запросов используется any
Route::any('/', function () {
    return view('welcome');
});

//Можем передавать контроллер и метод через "@"
Route::get('/', 'ControllerName@Method')
Route::get('/', 'HomeController@index')

//Или передавать массив в наш шаблон view
Route::get('/', 'uses' => 'HomeController@index', 'as' => 'home')

//Или передаем переменные
Route::get('/', function() {
    return view('name_template' , [
        'item' => 'value',
        'item2' => 'value2'
    ]);
});

Route::get('/', function() {
    return view('name_template')->with('item', 'value');
});

Route::get('/', function() {
    $item = value;
    return view('name_template', compact('item'));
});
//Что значит что должен отработаь метод index контролелра HomeController

//В роутах можно указывать в пути переменную, формата:
Route::get('message/{id}/edit', ['uses' => 'HomeController@edit', 'as' => 'message.edit'])->where(['id' => '[0-9]+']);
//where создает правило для переменной id, в данном примере id возможна только цифра.
//Функиця <b>dd($id)</b> => выводит дамп переменной $id

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', function () {
        return view('auth.register');
    });
});
			</pre>
		</div>
	</li>

    <li>
        <div class="collapsible-header">Шаблоны</div>
        <div class="collapsible-body">
            <a class="link" href="https://laravel.com/docs/5.6/blade">Офф докуменитация</a>
            <p>Шаблоны находится в <b>resources/views/</b></p>
            <p>Файлы шаблоны, имеют имя формата: template<b>.blade</b>.php</p>
            <div class="card-panel">
                <i>В расширяемом шаблоне:</i><br>
                - директива <b>@yield('Имя_заменяемой_части')</b> указывается в раширямом шаблоне, ставиться на том месте где необходимо дополнить материалом.<br><br>

                <i>В расширении шаблона:</i><br>
                - директива <b>@extends('Имя_расширяемого_шаблона')</b> указывается в шаблоне в качестве определения шаблона который мы расширяем.<br>
                - директива <b>@section('Имя_секции_для_вставки')</b> указывает в какое место будет вставлено дополнение шаблона, имя должно быть то, что указано в директиве <b>@yield</b><br>
                - закрытие директивы <b>@section('Имя_секции_для_вставки')</b> закрывается с помощью директивы <b>@stop('Имя_секции_для_вставки')</b><br><br>

                Подключение шаблона: <b>@include('Имя_подключаемого_шаблона')</b>. Формата:
            </div>
            <pre data-enlighter-language="php">
@extends('index')
//Расширяем, наследуем шаблон index

@section('content')
//Вставка расширения распологается в указанной секции @yield('content')

@include('_common._form')
//Подключаем шаблон с папки _common

@stop()
//Конец вставки

@parent
//Использование кода родителя

//Для указания вложенности шаблонов используется конструкцуия(в контроллере):
public function index() {
    return view ('page.messages.index');
}
            </pre>
            <h5>Передача переменных</h5>
            <pre data-enlighter-language="php">
//view принимает также параметр $date для передачи переменных в шаблон
public function index() {
$date = [
    'title' => 'Гостевая книга на laravel 5.6',
    'page_title' => 'Гостевая книга'
];
return view ('page.messages.index', $date);
}

//можно передать через ->with():
return view ('page.messages.index')->with($date);
return view ('page.messages.index')->with('title', 'Гостевая книга');
            </pre>
            <div class="card-panel">
                В шаблоне переменная будет выгледеть как <b>{{ $item }}</b>, а в передачи через массив в контроллере <b>['item' => 'Значение']</b><br>
                Контент переданный в переменную будет экранирован в <b>{{ }}</b>, для передачи html сущностей необходимо переменную обернуть в <b>{!! !!}</b>
            </div>
            <h5>Упровляющие конструкции</h5>
            <a class="link" href="https://laravel.com/docs/5.6/blade#control-structures">Офф документация</a>
        </div>
    </li>

    <li>
        <div class="collapsible-header">Миграции и работа с БД</div>
        <div class="collapsible-body">
            <a class="link" href="https://laravel.com/docs/5.6/migrations#generating-migrations">Офф. документация</a>
            <pre data-enlighter-language="php">
php artisan make:migration --help

//Создание миграции
php artisan make:migration create_message_table --create=имя_для_таблици

php artisan make:migration --table=имя_таблицы --path=путь/к/миграции имя_миграции
//--table=имя_таблицы указывается для создание конструктора под нужную таблицу

//Запуск миграции
php artisan migrate --path=путь/к/миграциям

//Откат всех миграции
php artisan migrate:reset

//Удоление всех БД и создание новых
php artisan migrate:refresh

//Откат последней миграции, или указание кол-ва миграций
php artisan migrate:rollback --step=5

php artisan make:migration --path=путь/для/миграции имя_миграции

            </pre>
            <p>При создании таблици в файле миграции указывается конструкция:</p>
            <pre data-enlighter-language="php">
Schema::create('message', function (Blueprint $table) {
    $table->string('email', 30);
    ...
});
            </pre>
            <p>При изменении таблици в файле миграции указывается конструкция:</p>
            <pre data-enlighter-language="php">
Schema::table('message', function (Blueprint $table) {
    $table->string('name', 40);
            ...
});
            </pre>
            <p>Для доступа к данным с БД</p>
            <pre data-enlighter-language="php">
//пример с роута, в $item получаем всю таблицу
$item = DB::table('name_table')->get();

//далее, например в view
<ul>
    @foreach ($tasks as $task)
    <li>{{ $task->name_field_in_table }}</li>
    @endforeach
</ul>

//Пример роута который передает инфу с БД по переданому в роут id
Route::get('/tasks/{id}', function($id) {
    $task = DB::table('name_table')->find($id);
    return view('welcome')->with('task', $task);
});
            </pre>
        </div>
    </li>

    <li>
        <div class="collapsible-header">Модели</div>
        <div class="collapsible-body">
            <a class="link" href="https://laravel.com/docs/5.6/eloquent#defining-models">Офф. документация</a>
            <pre data-enlighter-language="php">
php artisan make:model MyModel
            </pre>
            <p>Для создание модели и миграции одновренменно вводим ключь <b>-m</b>:</p>
            <pre data-enlighter-language="php">
php artisan make:model Models/MyModel -m
            </pre>
            <p>Запуск php интерпритатора в консоли:</p>
            <pre data-enlighter-language="php">
php artisan tinker

$msg = new message;
$msg->name = 'Vita';
$msg->email = 'vitia@yandex.ru';
$msg->message = 'First message';

//Запись в БД
$msg->save();

//Вывод всех записей
message::all();

//Получить запись по ID
message::find(1);

//Сортировака вывода
message::orderBy('created_at', 'desc'(по возврастанию/убыванию))->get()
//Имеются встроенные методы сортировки latest и oldest:
message::latest()->get()


//Обновление записи
$m->message = 'new message';
$m->save();

//Удаление
$m->delete();
            </pre>
            <p>Существует быстрый способ создание записи в БД через класс:</p>
            <pre data-enlighter-language="php">
message::create(['name' => 'Andrey', 'email' => 'andre@mail.ru', 'message' => 'My message']);
            </pre>
            <p>Для его использования нелбходимо в классе добавить записи с перечеслением полей который можно записывать таким образом в переменную:</p>
            <pre>
class message extends Model
{
    protected $fillable = ['user', 'email', 'message'];
}
            </pre>
            <h5>Пагинация</h5>
            <pre data-enlighter-language="php">
$date = [
    'title' => 'Гостевая книга на laravel 5.6',
    'page_title' => 'Гостевая книга',
    'messages' => message::latest()->paginate(1),
    'count' => message::count()
];
            </pre>
            <p><b>paginate()</b> принимает кол-во записей выводимых на странице, набор полей БД, ключь лля номера текущей страницы, номер страницы.</p>
            <p>Для вывода переключателей страниц используется метод render()</p>
            <pre data-enlighter-language="php">
{!! $messages->render() !!}
            </pre>
        </div>
    </li>


</ul>