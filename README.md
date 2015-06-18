#vCard generator

## Info
Sterc
Sander Drenth <sander@sterc.nl> v1.0  

What is vCard generator?
vCard generator is a script to generate a vCard (.vcf) file based on settings or template variables that makes it easy for visitors to add contacts to their contactlists on for example their mobile phone.

The following contactinformation is added to the vCard file:
Name;
Company name;
Address;
Fax number;
Phone number;
E-mail address;
A company logo/photo.

Usage

Install:
Simply place the file inside the /assets/snippets/ folder and create the client config settings which are specified below under requirements.

If you wish to use this script to get resource specific contact information you need to add the following (optional) template variables:
photo;
street;
housenumber;
zipcode;
city;
fax;
phone;
email.

The pagetitle is used to generate the name, the company names default is the context setting site_name.

Frontend usage:
Simply call the file to download the vCard automatically. To retrieve resource specific contactinformation just add the resource ID inside the GET paramater id.

Requirements
Clientconfig: Is being used to retrieve contact settings. The following client config keys are being used:
vcard_logo;
street;
housenumber;
zipcode;
city;
fax;
phone;
email_client.
Note: Can also be used without the client config package, the $settings variables need to be adapted to use the system settings.
