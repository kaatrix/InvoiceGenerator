The following project will:
1. Generate random invoices and save them to database
2. Send chosen invoice on specified email (as pdf attachment)
3. Generate csv files with one invoice (or all invoices) which can be used as import file for another system   

Every action will be available through REST Api.
Available routes:
    /add-new-invoices-to-database/{count}
    *use this route to generate given amount of new invoices and save them to database
    (count is your given amount of new invoices)

    /send-pdf-invoice-to-email/{invoiceNumber}/{emailAddress} 
    *use this route in order to send a given invoice number to a specified email address 
    ( invoiceNumer is your given invoice number, emailAddress is your given email address)

    /download-csv-invoices-package 
    *use this route to download a csv fine containing all invoices from database
    
    /download-csv-invoice-file/{invoiceNumber} 
    *use this route to download a csv file with a specified invoice 
    (invoiceNumber is your given invoice number)