# Модуль расширения отчётов `AOR_Reports`.
@version 1.0.0-SNAPSHOT

## Описание
Модуль позволяет создавать пользовательские (не стандартные) отчёты. Для этого в модуле заложены некоторые инструменты.

Пример отчёта:

`<ReportName>.php`

```php
<?php
/*
 *  Описание отчёта
 *
 * @id <ReportName>
 * @name Название отчёта
 * @deleted 0|1
 * @version Версия
 */

require_once "custom/modules/AOR_Reports/include/ICustomReport.php";
class <ReportName> extends ICustomReport
{
  // Заполнение необходимых полей
  // таких как `headers` и `columns`
    public function generate()
    {
      // Здесь код формирования отчёта и заполнения поля `results`
    }
}  

```

Первый комментарий должен содержать следующие данные:

`@id` ID отчёта совпадает с именем файла и класса не должен привышать 36 символов

`@name` Видимое имя отчёта

`@deleted` Признак удаления

Запись об отчёте загружается в БД автоматически из каталога `custom/modules/AOR_Reports/reports`, согласно полям в комментарии файла отчёта. Название директории с отчётом должно совпадать с названием исполняемого файла и с  ID отчёта.

## ICustomReport

Основной класс для формарования отчётов


* Class name: ICustomReport
* This is an **abstract** class

### Properties


#### $headers

    public array $headers = array()

Заголовок отчёта

* Visibility: **public**




#### $columns

    public array $columns = array()

Именование столбцов отчёта и их порядок в результатах

* Visibility: **public**




#### $results

    public array $results = array()

Строки (результаты) отчёта

* Visibility: **public**




#### $form

    public array $form = array()

`$_GET` данные, которые поступили в результате заполнения фильтра отчёта

* Visibility: **public**


#### $action

    public string $action

Поле `$_GET["action"]`

* Visibility: **public**


#### $record

    public string $record

Поле `$_GET["record"]`, соответствует ID отчёта

* Visibility: **public**


#### $title

    public string $title

Название отчёта для вывода на экран

* Visibility: **public**


#### $export_url

    public string $export_url

Адрес для формирования запроса на экспорт отчёта

* Visibility: **public**


#### $action_url

    public string $action_url

Адрес страницы без GET-запроса

* Visibility: **public**


#### $module_name

    public string $module_name = "Отчёт"

Название модуля

* Visibility: **public**


#### $template

    public string $template = "custom/modules/AOR_Reports/tpls/ReportSimple.tpl"

Шаблон для вывода на экран

* Visibility: **public**


#### $errors

    public array $errors = array()

Массив ошибок при формаровании отчёта

* Visibility: **public**


### Methods


#### __construct

    mixed ICustomReport::__construct()

Создание экземпляра объекта. Во время создания заполняются поля:

1. `form`
2. `action`
3. `record`
4. `export_url`
5. `action_url`
6. `title`

* Visibility: **public**


#### generate

    ICustomReport::generate()

Метод для формарования отчёта. Реализуется в клессе конкретного отчёта и вызывается для формирования отчёта. В последующем для вывода на экран используются шаблон установленный в поле `template` в качестве данных в `Smarty` отправляется отчёт в виде массива в переменную `report`. Таким образом, например,  в шаблоне для доступа к полю `record` необходимо использовать `$report.record`.

* Visibility: **public**
* This method is **abstract**.


#### getFormatTime

    string ICustomReport::getFormatTime($time)

Выводит секунды в формате `HH:mm:ss`

* Visibility: **public**


##### Arguments

* $time **int** время в секундах

#### export

    ICustomReport::export($format)

Экспорт отчёта. 

`$format` может принимать следующие значения: `csv`

* Visibility: **public**


##### Arguments

* $format **string** формат экспорта отчёта.

#### exportCSV

    ICustomReport::exportCSV()

Экспорт отчёта в формате `csv`

* Visibility: **public**


#### getActionURL

    string ICustomReport::getActionURL()

Получить текущий путь без  GET запроса.

* Visibility: **private**



