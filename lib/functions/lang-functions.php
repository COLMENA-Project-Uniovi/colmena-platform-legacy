<?php
if(!isset($_SESSION['lang_sufix'])){
	$lang_sufix = "_es";
}else{
	$lang_sufix = $_SESSION['lang_sufix'];
}

$text = array();

$text['_es']['point'] = ".";
$text['_en']['point'] = ",";


//HEADER

$text['_es']['header_slogan'] = "El proyecto COLMENA realiza un análisis sobre los errores ofreciendo información sobre el aprendizaje de la programación";
$text['_en']['header_slogan'] = "COLMENA project realises analytics over errores providing useful information about programming learning";

//MENU

$text['_es']['menu_home'] = "inicio";
$text['_en']['menu_home'] = "home";

$text['_es']['menu_subjects'] = "asignaturas";
$text['_en']['menu_subjects'] = "subjects";

$text['_es']['menu_users'] = "usuarios";
$text['_en']['menu_users'] = "users";

$text['_es']['menu_families'] = "Familias de errores";
$text['_en']['menu_families'] = "Error families";

$text['_es']['menu_profile'] = "mi perfil";
$text['_en']['menu_profile'] = "my profile";

$text['_es']['menu_welcome'] = "Bienvenido/a, ";
$text['_en']['menu_welcome'] = "Welcome, ";

$text['_es']['menu_logout'] = "Salir";
$text['_en']['menu_logout'] = "Logout";

$text['_es']['colmena_coeficient'] = "Coeficiente Colmena";
$text['_en']['colmena_coeficient'] = "Colmena Coeficient";

$text['_es']['colmena_coeficient_average'] = "Tu Coeficiente Colmena medio";
$text['_en']['colmena_coeficient_average'] = "Your Colmena Coeficient average";

$text['_es']['in_subject'] = "En esta asignatura";
$text['_en']['in_subject'] = "In this subject";

//HOME

$text['_es']['home_slogan_teacher'] = "Monitoriza las prácticas de programación";
$text['_en']['home_slogan_teacher'] = "Monitore the programming lessons";

$text['_es']['home_slogan_student'] = "Mejora tu conocimiento sobre programación";
$text['_en']['home_slogan_student'] = "Improve your programming knowledge";

$text['_es']['home_goal_teacher'] = "El objetivo de COLMENA es proveer información enriquecida y útil a los profesores y estudiantes para mejorar su proceso de programación";
$text['_en']['home_goal_teacher'] = "Our goal is provide with usefull information to teachers and students in order to improve the programming learning process.";

$text['_es']['home_goal_student'] = "El objetivo de COLMENA es facilitar a los estudiantes el conocimiento de la información sobre los errores que cometen a lo largo de las asignaturas que cursan.";
$text['_en']['home_goal_student'] = "Our goal is provide with usefull information to students in programming learning subjects.";

$text['_es']['home_select_teacher'] = "A continuación verás las asignaturas a las que tienes acceso, selecciona una asignatura para conocer el estado del grupo";
$text['_en']['home_select_teacher'] = "Select one subject of the following and start monitoring your students' errors";

$text['_es']['home_select_student'] = "Puedes ver tu perfil de usuario COLMENA o conocer los diferentes errores del catálogo";
$text['_en']['home_select_student'] = "See your COLMENA profile or select an error of the taxonomy";


$text['_es']['week'] = "Sesión";
$text['_en']['week'] = "Week";

$text['_es']['total_sessions_home'] = "Sesiones registradas";
$text['_en']['total_sessions_home'] = "Registered Sessions: ";

$text['_es']['total_users_home'] = "Usuarios participando";
$text['_en']['total_users_home'] = "Users participating";

$text['_es']['total_errors_home'] = "Errores detectados";
$text['_en']['total_errors_home'] = "Errors detected";

// SUBJECTS / SESSIONS
$text['_es']['total_users_actives'] = "Usuarios activos: ";
$text['_en']['total_users_actives'] = "Active users: ";

$text['_es']['total_participation'] = "Asistencia a sesiones: ";
$text['_en']['total_participation'] = "Sessions with participation: ";

$text['_es']['errors_over_total'] = "Porcentaje sobre el total de errores de la asignatura";
$text['_en']['errors_over_total'] = "Percentaje over total subject errors";

$text['_es']['total_errors'] = "Errores Totales: ";
$text['_en']['total_errors'] = "Total Errors: ";

$text['_es']['sessions_subject_title'] = "Sesiones de esta asignatura";
$text['_en']['sessions_subject_title'] = "Sessions of this subject";

$text['_es']['select'] = "Selecciona una sesión";
$text['_en']['select'] = "Select a session";

$text['_es']['total_errors_title'] = "Errores y Warnings totales por familia de error";
$text['_en']['total_errors_title'] = "TOTAL ERRORS AND WARNINGS BY FAMILY";

$text['_es']['evolution_errors_title'] = "Evolución del Coeficiente Colmena en las sesiones";
$text['_en']['evolution_errors_title'] = "EVOLUTION OF COLMENA COEFICIENTE THROUGH SESSIONS";

$text['_es']['users_family_title'] = "Usuarios ordenados por su Coeficiente Colmena en la asignatura";
$text['_en']['users_family_title'] = "Users grouped by Colmena Coeficient";

$text['_es']['top_errors_title'] = "Los Errores más frecuentes";
$text['_en']['top_errors_title'] = "Top more frequent errors";

$text['_es']['top_10_errors_title'] = "Los 10 Errores más frecuentes";
$text['_en']['top_10_errors_title'] = "Top 10 more frequent errors";

$text['_es']['top_user_errors_title'] = "Errores más frecuentes";
$text['_en']['top_user_errors_title'] = "Top frequent errors";

$text['_es']['sessions_report_title'] = "Reportes por sesión";
$text['_en']['sessions_report_title'] = "Session reports";

$text['_es']['comparison_title'] = "Comparación de sesiones";
$text['_en']['comparison_title'] = "Comparing sessions";

$text['_es']['comparison_resume'] = "Resumen de sesiones";
$text['_en']['comparison_resume'] = "Sessions resume";

$text['_es']['comparison_families'] = "Errores por familia en las sesiones";
$text['_en']['comparison_families'] = "FAMILIES ERRORS IN SESSIONS";

$text['_es']['comparison_user_errors'] = "Errores de usuarios comunes agrupados por familias en las sesiones";
$text['_en']['comparison_user_errors'] = "COMMON USERS/FAMILIES ERRORS IN SESSIONS";

$text['_es']['comparison_common_users'] = "Usuarios comunes a las dos sesiones";
$text['_en']['comparison_common_users'] = "Common users in sessions";

$text['_es']['top_10_best_users'] = "10 mejores usuarios";
$text['_en']['top_10_best_users'] = "Top ten users";

$text['_es']['legend_subject_sessions'] = "Una sesión práctica constituye un bloque dentro de una asignatura.";
$text['_en']['legend_subject_sessions'] = "One session represent subject block.";

$text['_es']['legend_subject_warnings'] = "Los warnings son advertencias del código, pero permiten compilar y ejecutar. Los errores en tiempo de compilación impiden que el código se compile.";
$text['_en']['legend_subject_warnings'] = "Warnings can be avoided, but compilation errors disallow to compile.";

$text['_es']['legend_subject_evolution'] = "El gráfico muestra los diferentes coeficientes Colmena a lo largo de las sesiones. Cuanto más cercano a 100, mejor puntuada se considera una sesión.";
$text['_en']['legend_subject_evolution'] = "Colmena Coeficient throug sessions. If Colmena Coeficient is close to 100, it means that the session was good.";

$text['_es']['legend_subject_users'] = "Los usuarios que tengan mayor coeficiente son los que han generado menos errores en la asignatura.";
$text['_en']['legend_subject_users'] = "Users with higher colmena coeficient are the generators of less errors in a subject.";

$text['_es']['legend_subject_errors'] = "Los errores que están representado en rojo son errores en tiempo de compilación mientras que los amarillos son solamente warnings.";
$text['_en']['legend_subject_errors'] = "Compilation errors are represented in red. Warnings are the yellow ones.";

$text['_es']['legend_errors_over_total'] = "El gráfico muestra el porcentaje de errores que representa la sesión sobre la asignatura";
$text['_en']['legend_errors_over_total'] = "Errors of this session over total subject errors";

$text['_es']['know_more_families'] = "Conoce más sobre las familias aquí";
$text['_en']['know_more_families'] = "Know more about families here";

$text['_es']['know_more_users'] = "Conoce más sobre los usuarios aquí";
$text['_en']['know_more_users'] = "Know more about users here";



//USERS
$text['_es']['user_summary']= "Resumen del usuario";
$text['_en']['user_summary']= "User Summary";

$text['_es']['session_summary']= "Resumen de las sesiones";
$text['_en']['session_summary']= "Session summary";

$text['_es']['times']= "Veces";
$text['_en']['times']= "Times";

$text['_es']['average']= "Media";
$text['_en']['average']= "Average";

$text['_es']['top_3_errors']= "Éstos son los 3 errores que has tenido más veces";
$text['_en']['top_3_errors']= "These are your top-3 errors";

$text['_es']['cc_in']= "Coeficiente Colmena en";
$text['_en']['cc_in']= "Colmena Coeficiente in";

$text['_es']['subjects']= "asignatura/s";
$text['_en']['subjects']= "subject/s";

$text['_es']['user_subject']= "Asignaturas del usuario";
$text['_en']['user_subject']= "User subjects";

$text['_es']['total_errors_in_subject']= "Errores";
$text['_en']['total_errors_in_subject']= "Errors";

$text['_es']['percentage_of_total']= "del total";
$text['_en']['percentage_of_total']= "over total";

$text['_es']['you_are']= "Posición:";
$text['_en']['you_are']= "Ranking:";

$text['_es']['position_long']= "Tu posición en la asignatura:";
$text['_en']['position_long']= "Your ranking in subject:";

$text['_es']['total_users_ranking']= "Usuarios de la asignatura";
$text['_en']['total_users_ranking']= "Users in subject";

$text['_es']['of']= "de";
$text['_en']['of']= "of";

$text['_es']['no_errors']= "No hay errores registrados";
$text['_en']['no_errors']= "There is no registered errors";

$text['_es']['see_more']= "Ver más";
$text['_en']['see_more']= "See more";

$text['_es']['top_errors']= "Top de errores";
$text['_en']['top_errors']= "Top errors";

$text['_es']['table_name']= "Nombre";
$text['_en']['table_name']= "Name";

$text['_es']['table_message']= "Mensaje";
$text['_en']['table_message']= "Message";

$text['_es']['table_times']= "Apariciones";
$text['_en']['table_times']= "Times";

$text['_es']['%aparitions']= "% sobre el total";
$text['_en']['table_times']= "% over total";

$text['_es']['table_type']= "Tipo";
$text['_en']['table_type']= "Type";

$text['_es']['similar_users']= "Usuarios similares";
$text['_en']['similar_users']= "Similar users";

$text['_es']['no_similar_users']= "No hay usuarios similares a ";
$text['_en']['no_similar_users']= "There are no similar users to ";

$text['_es']['p_numberone']= "¡Eres un crack!";
$text['_en']['p_numberone']= "Awesome!";

$text['_es']['p_numbertwo']= "¡Casi el mejor!";
$text['_en']['p_numbertwo']= "Almost the best!";

$text['_es']['p_numberthree']= "¡De los 3 mejores!";
$text['_en']['p_numberthree']= "In the top-3!";

$text['_es']['p_excelent']= "¡Eres de los buenos!";
$text['_en']['p_excelent']= "You rule!";

$text['_es']['p_good']= "¡Vas por buen camino!";
$text['_en']['p_good']= "Good job!";

$text['_es']['p_regular']= "¡No te despistes!";
$text['_en']['p_regular']= "Borderline!";

$text['_es']['p_low']= "Necesitas un empujón";
$text['_en']['p_low']= "You need work a little bit more";

$text['_es']['session']= "Sesión";
$text['_en']['session']= "Session";

$text['_es']['session_ausence']= "Parece que no has ido a clase...";
$text['_en']['session_ausence']= "Parece que no has ido a clase...";

$text['_es']['legend_user_summary'] = "Conoce el Coeficiente Colmena del usuario en general y los errores más comunes";
$text['_en']['legend_user_summary'] = "Know Colmena Coeficient and user's more frequent errors";

$text['_es']['legend_user_subject'] = "Aquí podrás ver un breve resumen de las asignaturas donde participó el usuario.";
$text['_en']['legend_user_subject'] = "Here you have an extract about the user in subjects where has participated";

$text['_es']['legend_user_subject'] = "Aquí podrás ver un breve resumen de las asignaturas donde participó el usuario.";
$text['_en']['legend_user_subject'] = "Here you have an extract about the user in subjects where has participated";

$text['_es']['legend_similar_users'] = "Usuarios con un Coeficiente Colmena similar en las mismas asignaturas que el usuario.";
$text['_en']['legend_similar_users'] = "Users who have a similar Colmena Coeficient in the same subject as user.";

$text['_es']['about_cc_title'] = "Sobre el Coeficiente Colmena";
$text['_en']['about_cc_title'] = "About Colmena Coeficient";

$text['_es']['about_cc_p1'] = "El coeficiente colmena es un coeficiente que se calcula a partir de los errores que un usuario comete durante la programación. Se consideran todas las sesiones de las asignaturas donde haya participado desde que se registró en el sistema.";
$text['_en']['about_cc_p1'] = "Colmena Coeficient is a coeficient calculated throug errors that user generates during programming. All sessions where user has participated since first register in system are considerated.";

$text['_es']['about_cc_p2'] = "Sus valores van de 0 hasta 100. Cuanto más próximo a 100 esté, menos errores se habrán detectado de esa familia. Si se aproxima mucho a 0, significa que el usuario tiene muchos errores de esa familia.";
$text['_en']['about_cc_p2'] = "The value of Colmena Coeficient is between 0 and 100. If it is closer to 100, user has had less errors in the specific family. If it is close to 0, it means that user has generated a lot of errors in this family.";


//USERS IN SUBJECTS 

$text['_es']['generated_errors'] = "Errores y Warnings generados";
$text['_en']['generated_errors'] = "Errors & warnings generated";

$text['_es']['compilations_in_session'] = "Errores generados durante la sesión";
$text['_en']['compilations_in_session'] = "Errors generated through session";

$text['_es']['legend_compilations_in_session'] = "Compilaciones del usuario a lo largo de la sesión con el número de errores en cada una de ellas";
$text['_en']['legend_compilations_in_session'] = "Errors generated through session";

$text['_es']['you_have']= "Has tenido";
$text['_en']['you_have']= "You've had";

$text['_es']['with']= "Con";
$text['_en']['with']= "With";

$text['_es']['more_than_average']= "Más que la media";
$text['_en']['more_than_average']= "More than average";

$text['_es']['less_than_average']= "Menos que la media";
$text['_en']['less_than_average']= "Less than average";

$text['_es']['in_the_average']= "Justo en la media";
$text['_en']['in_the_average']= "In average";

$text['_es']['your_top5_errors']= "Los 5 errores que más veces has tenido";
$text['_en']['your_top5_errors']= "Your top 5 errors";

$text['_es']['average_top5_errors']= "Los 5 errores que más han aparecido en general";
$text['_en']['average_top5_errors']= "Average top 5 errors";

$text['_es']['in'] = "en";
$text['_en']['in'] = "in";

$text['_es']['legend_evolution_erros_title'] = "Evolución del coeficiente colmena del usuario por sesión en la asignatura.";
$text['_en']['legend_evolution_erros_title'] = "Evolution of Colmena Coeficient in subject by session";

$text['_es']['legend_top_user_errors_title'] = "Errores más frecuentes del usuario en la asignatura.";
$text['_en']['legend_top_user_errors_title'] = "Most frequent user errors in subject.";

$text['_es']['legend_sessions_report'] = "Informe detallado de la actividad del usuario en cada sesión";
$text['_en']['legend_sessions_report'] = "Detailed report of user's activity in each session.";

$text['_es']['legend_colmena_coeficient'] = "Coeficiente Colmena del usuario en la sesión";
$text['_en']['legend_colmena_coeficient'] = "Colmena Coeficient of user in session";

$text['_es']['legend_generated_errors'] = "Errores generados por el usuario en la sesión completa, comparándolos con la media de los participantes en la sesión.";
$text['_en']['legend_generated_errors'] = "Errores generated by user in the whole sesion, in comparison with the complete group of participants.";

$text['_es']['legend_compilations_in_session'] = "Evolucion de los errores que el usuario generó en la sesión.";
$text['_en']['legend_compilations_in_session'] = "Evolution of the ererros generated by usen in session.";




//ERRORS

$text['_es']['families_title'] = "Conoce y documenta cada error";
$text['_en']['families_title'] = "Know and comment each different error";

$text['_es']['families_description'] = "Una familia de error es una categoría que define un propósito general de un grupo de errores. Esta clasificación facilita saber que tipos de error son más frecuentes. También es muy útil para estudiantes, que pueden comparar sus errores en base a las familias donde se encuentran. </p><p>La estructura de familias se bassa en la definición de la interfaz IProblem de Eclipse, que se usa internamente para asignar códigos de error y clasificarlos.";
$text['_en']['families_description'] = "An error family is a category that defines de general purpose of the error that belongs to. This categorization makes easier to identify what kind of error is more frequent. This is also very useful for students, who can compare their errors associating the family where it is located. </p><p>The classification is based on Eclipse IProblem interface, what is used internally by Eclipse to assign codes to errors and classify them into groups.";

$text['_es']['families_description_student'] = "Bienvenido a las diferentes familias de errores.";
$text['_en']['families_description_student'] = "Welcome to the different families errors";

$text['_es']['name'] = "Nombre";
$text['_en']['name'] = "Name";

$text['_es']['times_that_appear'] = "Veces que aparece en total";
$text['_en']['times_that_appear'] = "Times that appear";

$text['_es']['times_that_appear_user'] = "Veces que lo has cometido";
$text['_en']['times_that_appear_user'] = "Times you've made it";

$text['_es']['users_who_have'] = "Usuarios que lo tienen";
$text['_en']['users_who_have'] = "Users who have";

$text['_es']['message'] = "Mensaje";
$text['_en']['message'] = "Message";

$text['_es']['subjects_where_appear'] = "Asignaturas donde aparece";
$text['_en']['subjects_where_appear'] = "Subjects where appear";


$text['_es']['edit'] = "Editar";
$text['_en']['edit'] = "Edit";

$text['_es']['cancel'] = "Cancelar";
$text['_en']['cancel'] = "Cancel";

$text['_es']['confirm-cancel'] = "Si continúas se perderán todos los cambios, ¿deseas continuar?";
$text['_en']['confirm-cancel'] = "You'll lost all changes, would you like to continue?";

$text['_es']['save'] = "Guardar cambios";
$text['_en']['save'] = "Save changes";

$text['_es']['custom_examples_errors_title'] = "Tus ejemplos más comunes de este error";
$text['_en']['custom_examples_errors_title'] = "Your usual examples of this error";

$text['_es']['custom_examples_errors_title_teacher'] = "Los ejemplos más comunes de este error";
$text['_en']['custom_examples_errors_title_teacher'] = "Usual examples of this error";

$text['_es']['ejemplos-no-registrados'] = "Aún no hemos registrado ningún ejemplo para este error";
$text['_en']['ejemplos-no-registrados'] = "There isn't an example for this error";


$text['_es']['examples_errors_title'] = "Causas y soluciones para este error";
$text['_en']['examples_errors_title'] = "Causes and solutions of this error";

$text['_es']['add_new_example'] = "Añadir un nuevo ejemplo";
$text['_en']['add_new_example'] = "Add new example";

$text['_es']['edit_example'] = "Editar ejemplo";
$text['_en']['edit_example'] = "Edit example";

$text['_es']['detected_errors'] = "Tipos de Errores";
$text['_en']['detected_errors'] = "Error types";

$text['_es']['sort_by'] = "Ordenar por:";
$text['_en']['sort_by'] = "Sort by:";

$text['_es']['sort_direction'] = "Ascendente/Descendente";
$text['_en']['sort_direction'] = "Sort direction";

$text['_es']['direct_search'] = "Búsqueda directa por nombre técnico del error";
$text['_en']['direct_search'] = "Direct search by tecnical name";

$text['_es']['wrong_usage'] = "Ejemplo de código que genera este error";
$text['_en']['wrong_usage'] = "Code example where error appears";

$text['_es']['complete_wrong_message'] = "Mensaje devuelto para el código incorrecto";
$text['_en']['complete_wrong_message'] = "Output for this wrong code";

$text['_es']['right_usage'] = "Ejemplo de código donde se soluciona este error";
$text['_en']['right_usage'] = "Right usage where error does not happen";

$text['_es']['solution'] = "Manera de solucionar el error";
$text['_en']['solution'] = "Way to solve the error";

$text['_es']['explanation'] = "Explicación del ejemplo";
$text['_en']['explanation'] = "Example Explanation";

$text['_es']['references'] = "Referencias";
$text['_en']['references'] = "References";

$text['_es']['concepts'] = "Conceptos";
$text['_en']['concepts'] = "Concepts";

$text['_es']['start_line_right'] = "Línea de comienzo de la zona corregida";
$text['_en']['start_line_right'] = "Start line of corrected section";

$text['_es']['start_line_wrong'] = "Línea de comienzo de la zona a corregir";
$text['_en']['start_line_wrong'] = "Start line of section to be corrected";

$text['_es']['end_line_right'] = "Línea de fin de la zona corregida";
$text['_en']['end_line_right'] = "End line of corrected section";

$text['_es']['end_line_wrong'] = "Línea de fin de la zona a corregir";
$text['_en']['end_line_wrong'] = "End line of section to be corrected";

$text['_es']['source_code'] = "Código fuente";
$text['_en']['source_code'] = "Source code";


//FOOTER

$text['_es']['colmena_project'] = "Proyecto Colmena, ";
$text['_en']['colmena_project'] = "Colmena Project, ";

$text['_es']['university'] = "Universidad de Oviedo, España";
$text['_en']['university'] = "University of Oviedo, Spain";


// ERROR PAGES
$text['_es']['error-404'] = "Error 404 - Archivo no encontrado";
$text['_es']['error-404-message'] = "La página que está buscando no existe";
$text['_es']['error-403'] = "Error 403 - Acceso denegado";
$text['_es']['error-403-message'] = "No tienes permiso para acceder a esta página";

$text['_en']['error-404'] = "Error 404 - File not found";
$text['_en']['error-404-message'] = "The page you are looking for could not be found.";
$text['_en']['error-403'] = "Error 403 - Access is denied";
$text['_en']['error-403-message'] = "You don't have permission to access this page.";

?>