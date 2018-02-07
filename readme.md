Сайт-визитка
============
*`По мере наполнения здесь будут указаны используемые технологи, методы и приемы`*
<br>
1. Список задач смотреть в _task_list.md_
2. Проект строится по __MVC__ подробнее ниже
    Вся логика находятся в директории __protected__<br>
    В последствии будет реализован механизм защиты.
3. Структура проекта следующая:
    + css                - стили
    + fonts              - шрифты
    + not_used_ on_ site - нужное для построения сайта на этапе создания.
    + protected          - защищенная часть проекта
        * __с__     - controller
        * __m__     - model
            - img - изображения
                - db  - изображения DataBase
            - js  - JavaScript
        * __v__   - view
            - template - составные части шаблонов
        * __config__- конфигурационные файлы
            - initial_setup - первоначальные настройки