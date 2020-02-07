ICustomReport
===============

Основной класс для формарования отчётов




* Class name: ICustomReport
* Namespace: 
* This is an **abstract** class





Properties
----------


### $headers

    public array $headers = array()

Заголовок отчёта



* Visibility: **public**


### $columns

    public array $columns = array()

Именование столбцов отчёта и их порядок в результатах



* Visibility: **public**


### $results

    public array $results = array()

Строки (результаты) отчёта



* Visibility: **public**


### $form

    public array $form = array()





* Visibility: **public**


### $action

    public string $action





* Visibility: **public**


### $record

    public string $record





* Visibility: **public**


### $title

    public string $title





* Visibility: **public**


### $export_url

    public mixed $export_url





* Visibility: **public**


### $action_url

    public mixed $action_url





* Visibility: **public**


### $module_name

    public mixed $module_name = "Отчёт"





* Visibility: **public**


### $template

    public mixed $template = "custom/modules/AOR_Reports/tpls/ReportSimple.tpl"





* Visibility: **public**


### $errors

    public mixed $errors = array()





* Visibility: **public**


Methods
-------


### __construct

    mixed ICustomReport::__construct()





* Visibility: **public**




### generate

    mixed ICustomReport::generate()

метод для формарования отчёта.



* Visibility: **public**
* This method is **abstract**.




### getFormData

    array ICustomReport::getFormData()





* Visibility: **public**




### getURLs

    mixed ICustomReport::getURLs()





* Visibility: **public**




### getFormatTime

    mixed ICustomReport::getFormatTime($time)





* Visibility: **public**


#### Arguments
* $time **mixed**



### export

    mixed ICustomReport::export($format)





* Visibility: **public**


#### Arguments
* $format **mixed**



### exportCSV

    mixed ICustomReport::exportCSV()





* Visibility: **public**




### getActionURL

    mixed ICustomReport::getActionURL()





* Visibility: **private**



