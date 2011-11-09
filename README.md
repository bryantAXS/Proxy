NOTE: This is not production worthy yet.  Please give it a go if you would like to test, but I would not recommend using it on a production site.

#Proxy

Proxy is an EE2.0 Module used to substitue template tags with placeholder data, in order to more efficiently and effectively test your templates.

Before Proxy, I was creating a plethora of test entires to test various types of content that could be used in a Channel Field.  The process of creating all these dummy entries was time consuming, confusing, and led to me not testing as rigarously as I should have been.  Proxy was created to speed up the testing of templates, by allowing the developer to quicky swap in placeholder tag data when the template renders.

Follow me <a href='http://www.twitter.com/bryant_'>here</a> for more release updates.

##Requirements

* EE 2.0
* jQuery

##Installation
1. Add-ons -> Modules -> Proxy -> Install
2. Install both the Module and the Extension
3. Turn on the module by toggling the Substitution Enabled setting.

##Usage
- After turning on the Module by togging the Substituion Enabled setting, click on the Field Settings button.
- On the field settings field, select the Channel and Channel Field you want to setup a placeholder for, then click the + button.
- After the new field table has been created, set the preferences for that field:

		- Substitution Type: Lets you decide when to substitue data. (Substitute only when empty does not current work for tagpair substitution)
		- Substitution Method: How to substitue data; Either randomly, based on the placeholders you provide, or using the Placeholder Index, where you provide the index number of the placeholder which you want to use.
		- Placeholder(s):  The actual data you want to substitute.  For single tags, you can delimit multiple options using || . For tagpairs with child tags (ie: Matrix tags), use the format N||{field_1::value_1,,field_2::value_2}{field_1::value_3,,field_2::value_4}, where N is the number of times you want the tagpair to loop.

###Tagpair Example:  

If you have a matrix field called People, with two cells called person_name and person_age, and you want that tagpair to loop three times.

`3||{person_name::bryant,,person_age::27}{person_name::barclay,,person_age::17}`

In the previous example, if we have the Substitution Method set to Random, it will loop 3 times, selecting one of the data sets at random each time.  If the Substitution Method is set to Placeholder Index, and you provide the index 0, it will loop three times, always displaying the first dataset (index 0).

##Template Tags

There are no module specific template tags at this time.

## Known Issues
- Substituion Only Empty/Blank Data does not currently work for tagpair data.