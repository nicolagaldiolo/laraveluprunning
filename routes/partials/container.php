<?php

/*
 * CONTAINER
 *
 * Il container di  Laravel è uno strumento per gestire le dipendenze delle classi ed eseguire la Dependency Injection
 *
 * Dependency Injection significa che un oggetto anziché essere istanziato all'interno di una classe, esso gli viene
 * iniettato direttamente dall'esterno. In questo modo si può applicare la "Inversion of control"
 * "Inversion of control"
 *
 * L'inversione di controllo è un concetto che va a braccetto con la dependency injection e si effettua una vera e
 * propria inversione di controllo. Nella programmazione tradizionale le classi che istanziano altri oggetti sanno
 * esattamente quale istanza di oggetto includere, es: se instanzi un mailer sai esattamente se l'oggetto sarà di
 * tipo Mailgun, Mandrill or SendGrid.
 * Con il concetto di "Inversion of control" la definizione di quale classe Mailer usare risiede ad un livello più
 * astratto, es nella configurazione. La nostra classe sa solo che dovrà includere un oggetto di Tipo Mailer, ma
 * non sa quale di preciso; è il framework che sa quale istanza di oggetto iniettare. Questo rende il
 * codice molto più modulare e riutilizzabile.
 *
 */

Route::get('dependency_injection', 'Container@dependencyInjection');
Route::get('app_global_helper', 'Container@appGlobalHelper');