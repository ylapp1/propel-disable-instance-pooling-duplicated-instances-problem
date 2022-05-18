# propel-disable-instance-pooling-duplicated-instances-problem
Reproduce Propel duplicated instances problems.

Problem:

Propel models rely on the global Propel instance pool to properly set up parent/child relations when loading child objects during a `get<ModelName>s` query. Child objects do not have a related parent object until you call `get<ModelName>` and then it checks the instance pool or loads a new copy from the database, which results in multiple model instances referring to the same database record if instance pooling is disabled.

*Author::getBooks() only loads the child objects but does not tell them that it is their parent Author*

https://github.com/ylapp1/propel-disable-instance-pooling-duplicated-instances-problem/blob/4c6e966532276741604f7f09a28968c55d29e233/models/om/BaseAuthor.php#L910-L935

*Instead Propel expects Book::getAuthor() to load the parent Author from the instance pool to avoid duplicate instances for the same Author*

https://github.com/ylapp1/propel-disable-instance-pooling-duplicated-instances-problem/blob/4c6e966532276741604f7f09a28968c55d29e233/models/om/BaseBook.php#L913-L927

https://github.com/ylapp1/propel-disable-instance-pooling-duplicated-instances-problem/blob/4c6e966532276741604f7f09a28968c55d29e233/models/om/BaseAuthorPeer.php#L710-L727
