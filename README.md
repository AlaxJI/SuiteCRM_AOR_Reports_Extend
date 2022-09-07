# Модуль расширения отчётов `AOR_Reports`.
@version 2.0.1.1-SNAPSHOT

## Описание
Расширение позволяет создавать пользовательские (не стандартные) отчёты. Для этого в заложены некоторые инструменты.

Пользовательские отчёты следует создавать в папке `custom/lib/Modules/AOR_Reports/`. Каждый отчёт распологается в отдельной папке и должен содержать следующие файлы:

- `Report.php` - Сам отчёт. Наследуется от `SuiteCRM\Custom\Modules\AOR_Reports\AReport`
- `reportdefs.php` - матаданные для определение полей отчёта и полей поиска.

Так же отчёт может содержать:

- `language/en_us.lang.php`  - языковой файл для Английского языка (США), а так же другие языковые файлы.
- `tpls/ReportGenericRows.tpl` - для кастомизации вида отчёта
- `tpls/ReportHeader.tpl` - для кастомизации заголовка отчёта
- `tpls/ReportFooter.tpl` - для кастомизации ... отчёта
- `ReportData.php` - для расширения строки/столбца (единицы) отчёта. Наследуется от `SuiteCRM\Custom\Modules\AOR_Reports\ReportData`
- `ReportView.php` - для расширения вывода отчёта в UI. Наследуется от `SuiteCRM\Custom\Modules\AOR_Reports\ReportView`
- `ReportViewSmarty.php` - для расширения вывода отчёта в UI. Наследуется от `SuiteCRM\Custom\Modules\AOR_Reports\ReportViewSmarty`

Пример отчёта, смотри `custom/lib/Modules/AOR_Reports/Example`.

### `Report.php`

```php
<?php
/*
 *  Описание отчёта
 *
 * @id <ReportName>
 * @name Название отчёта
 * @deleted 0|1
 * @version Версия отчёта, если необходимо
 */
namespace SuiteCRM\Custom\Modules\AOR_Reports\<ReportName>;

use SuiteCRM\Custom\Modules\AOR_Reports\AReport;

class Report extends AReport
{
    /**
     * Формирует SQL-запрос для автоматической обработки отчёта
     */
    public function getSqlTemplate()
    {
        $sql = '';
				// SQL-Запрос должен содержать место для вставки условий where
        return $sql;
    }
}

```

Первый комментарий должен содержать следующие данные:

`@id` ID отчёта совпадает с именем файла и класса не должен привышать 36 символов

`@name` Видимое имя отчёта

`@deleted` Признак удаления

Запись об отчёте загружается в БД автоматически из каталога c отчётом, согласно полям в комментарии файла отчёта. Название директории с отчётом должно совпадать с названием исполняемого файла и с  ID отчёта.

## AReport

Основной класс для формарования отчётов. Позволяет создавать достаточно простые отчёты без дополнительных трудозатрат. Так же может быть дополнен или переделан в самом отчёте для получения более сложных отчётов.


* Class name: AReport
* This is an **abstract** class
* Наследуется от SugarBean

### Properties

#### $dataList

    protected array $dataList = []

Массив строк/столбцов (единицы) отчёта в виде массива. Массив единицы отчёта с ключами в ВЕРХНЕМ_РЕГИСТРЕ. Заполняется при построении отчёта.

* Visibility: **protected**



#### $dataReports

    protected array $dataReports = []

Массив строк/столбцов (единицы) отчёта в виде объектов `ReportData`. Заполняется при построении отчёта.

* Visibility: **protected**



#### $db

    public \DBManager $dataReports

Указатель на объект базы данных. Заполянется при содании объекта.

* Visibility: **public**



#### $defs

    public array $defs

Метаданные об отчёте. Заполняются обогащёнными данными из файла `reportdefs.php ` при содании объекта.

* Visibility: **public**



#### $field_defs

    public array $field_defs

Метаданные полей отчёта. Заполняются из файла `reportdefs.php ` при создании объекта. Оставлен для совместимости в SugarBean.

* Visibility: **public**



#### $field_name_map

    public array $field_name_map

Метаданные полей отчёта. Заполняются из файла `reportdefs.php ` при создании объекта. Оставлен для совместимости в SugarBean.

* Visibility: **public**



#### $module_dir

    public string $module_dir

Директория модуля (отчёта) Заполняется автоматически при создании объекта и имеет вид `AOR_Reports/<ReportName>`. Служит для совместимости с SugarBean.

* Visibility: **public**




#### $name

    public string $name

Имя отчёта. Заполняется автоматически при создании объекта из `namespace`.

* Visibility: **public**



#### $sql

    public string $sql

Полный SQL-запрос отчёта.

* Visibility: **public**



#### $table_name

    public string $table_name = 'report'

Имя таблицы в БД. Не менять. Оставлен для совместимости с SugarBean. Таблица `report` не создаётся в БД, а значение является заглушкой.

* Visibility: **public**




### Methods


#### __construct

    mixed AReport::__construct()

Создание экземпляра объекта. Во время создания заполняются поля:

1. `db`
2. `name`
3. `module_dir`
4. `defs`
5. `field_defs`
6. `field_name_map`

* Visibility: **public**



#### afterParseRow

    array AReport::afterParseRow($reportData)

Вызывается функция во время преобразования массива с данными в объект - единицу отчёта, после того как был получен объект с данными, но перед сохранением данных в массив. Преднозначен для работы с данными в конечном отчёте.

* Visibility: **protected**

##### Arguments

`$reportData` объект данных единицы отчёта





#### countSql

    string AReport::countSql($sql)

Метод формирует и выдаёт SQL-запрос для подсчёта количество данных в отчёте. *Сейчас не применяется. Для будущего использования.*

* Visibility: **public**

##### Arguments

`$sql` SQL-запрос для формирования запроса подсчёта количество данных в отчёте



#### generate

    AReport::generate(int $offset = -1, int $limit = -1)

Метод для формарования отчёта. Запрашивает SQL-Запрос (см. `AReport::getSql()`). Формирует лимиты данных (см. `AReport::limitSql($sql, $offset, $limit)`  в текушей версии не поддерживается).  Выполняет запрос и заполняет массивы `AReport::dataList` и`AReport::dataReports` через `AReport::processRow()`

`$offset` принимает значения: `>= 0` для определения смещения,   `-1` - данные будут браться из настройки системы, `< -1`  - смещение не будет определено в запросе.

`$limit` количество строк для получение данных в отчёт, по-умолчавнию, `-1`.

* Visibility: **public**



##### Arguments

`$offset` смещение для получение данных в отчёт, по-умолчавнию, `-1`.

`$limit` количество строк для получение данных в отчёт. По-умолчавнию, `-1`, что соответствет тому, что данные будут браться из системы. Если значение `< -1` , то ограничение на количество строк не будет определено в запросе.



#### getDataList

    array AReport::getDataList()

Возвращает массив строк/столбцов (единицы) отчёта в виде массива. Массив единицы отчёта с ключами в ВЕРХНЕМ_РЕГИСТРЕ. Заполняется при построении отчёта. **Может быть перезаписана в классе отчёта.**

* Visibility: **public**



#### getSql

    string AReport::getSql()

Формирует SQL-запрос из шаблона и блока `where`.

* Visibility: **public**



#### getSqlTemplate

    abstract string AReport::getSqlTemplate()

Метод должен быть объявлен в конечном отчёте и возвращать шаблон SQL-запроса. Шаблон служит для автоматического формирования запроса с условиями. В шаблон будут подставлены: условия по полям с первым AND, либо пустые строки, если поиск по полям не производится, либо обобщённое условие. Условия по полям подменяется по шаблону `<field_name>`. Обобщённое условие - по шаблону `<where>`. Для корректной работы запроса необходимо предусмотреть случай, когда условия не заданы, для этого в выражение WHERE необходимо включить постоянно выполняющееся условие, например, `... WHERE 1=1 <where>` или `... WHERE deleted=0 <field_name_1> <field_name_2> ... <field_name_n>`.

* Visibility: **public**
* This method is **abstract**.



#### getWhere

    array AReport::getWhere()

Через класс `SearchForm` формирует условия запроса и приводит уловия к массиву вида, где ключ - это поле отчёта, значение элемента - это шаблон условия где вместо поля отчёта устанавливается его шаблон вида `<field_name>`, которые впоследствии можно заменить на необходимое значение вида, например, `table.field`.

* Visibility: **protected**

##### See

[AReport::parseWhere](#parseWhere)



#### limitSql

    string AReport::limitSql($sql, $offset = -1, $limit = -1)

Добавляет к SQL-запросу данные для смещения и ограничения строк запроса.

`$offset` принимает значения: `>= 0` для определения смещения,   `-1` - данные будут браться из настройки системы, `< -1`  - смещение не будет определено в запросе.

`$limit` принимает значения: `>= 0` для определения количества получаемых строк напрямую,   `-1` - данные будут браться из настройки системы, `< -1`  - количество получаемых строк не будет определено в запросе.

* Visibility: **public**


##### Arguments

* `$sql` SQL-запрос к которому будет добавлены данные для смещения и ограничения строк запроса
* `$offset` смещение для получение данных в отчёт, по-умолчавнию, `-1`.
* `$limit` количество строк для получение данных в отчёт, по-умолчавнию, `-1`.



#### parseDefs

    array AReport::parseDefs($reportdefs)

Разбирает медатанные отчёта, обогащает их необхордимыми данными, загружает языковой пакет и возвращает обработанные метаданнные

* Visibility: **protected**

##### Arguments

* `$reportdefs` Массив с сырыми метаданными отчёта



#### parseWhere

    array AReport::parseWhere($where)

Формирует массив для подстановки условий в шаблон SQL-запроса.

`$where` - массив с шаблонами условий

* Visibility: **public**



##### Arguments

* `$where` Массив с шаблонами условий



#### prepareMetaData

    self AReport::prepareMetaData()

Загружает метаданные из файла `custom/lib/Modules/AOR_Reports/<reportName>/reportdefs.php`, отдаёт их на обработку и заполняет  необходимые поля объекта.

* Visibility: **protected**



#### processLanguage

    self AReport::processLanguage()

Загружает языковые данные отчёта из папки `custom/lib/Modules/AOR_Reports/<reportName>/language` в соответствии с языковым пакетом пользователя и кеширует их для дальнейшего использования при выводе отчёта в UI.

* Visibility: **protected**



#### processRow

    \SuiteCRM\Custom\Modules\AOR_Reports\ReportData AReport::processRow($row)

Преобразует массив с данными в объект - единицу отчёта. Заполняет массивы `AReport::dataList` и`AReport::dataReports` после преобразования массива в объект с данными.

`$row` - массив с данными БД

* Visibility: **public**



##### Arguments

* `$row` Массив с данными БД



#### toArray

    array AReport::toArray()

Метод добавлен для совместимости с `SugarBean`  в `SearchForm`. Выводит все поля из определения.

* Visibility: **public**

