## Задание
Вы разрабатываете сервис для рассылки уведомлений об истекающих подписках.
Примерно за три дня до истечения срока подписки, нужно отправить письмо пользователю с текстом "{username}, your subscription is expiring soon".

Имеем следующее
Таблица users в DB с пользователями (1 000 000 строк): username — имяemail — емейлvalidts — unix ts до которого действует ежемесячная подписка confirmed — 0 или 1 в зависимости от того, подтвердил ли пользователь свой емейл по ссылке (пользователю после регистрации приходит письмо с уникальный ссылкой на указанный емейл, если он нажал на ссылку в емейле в этом поле устанавливается 1)
Таблица emails в DB с данными проверки емейл на валидность: email — емейлchecked — 0 или 1 (был ли проверен) valid — 0 или 1 (является ли валидным)
Функция check_email( $email )Проверяет емейл на валидность и возвращает 0 или 1. Функция работает от 1 секунды до 1 минуты. Вызов функции платный.

## Проделанная работа
Для выполнения задания выбран фреймворк Laravel. Основной код находится в `app/Console/Kernel.php` (Task Scheduling), `app/Mail/UserEmail.php` (Объект письма).
Подразумевается существования пункта `Имеем следующее`, по этому реализация всего что там описано отсувтует, по этой же причине проект носит абстрактный характер и не подлежит тестовому запуска в следсвии отствия необходимого функционала для работы.