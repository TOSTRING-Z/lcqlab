create table if not exists `TF-Marker`.main
(
	PMID int null,
	GeneName text null,
	GeneType text null,
	ControlMarker text null,
	CellName varchar(255) null,
	CellType varchar(255) null,
	TissueType varchar(255) null,
	ExperimentType text null,
	ExperimentalMethod text null,
	Title text null,
	Function text null,
	GeneNameText text null,
	Interacting_Gene_Symbol text null,
	id int auto_increment comment '主键'
		primary key
)
charset=utf8mb4;

create index main_CellName_index
	on `TF-Marker`.main (CellName);

create index main_CellType_index
	on `TF-Marker`.main (CellType);

create index main_TissueType_index
	on `TF-Marker`.main (TissueType);
