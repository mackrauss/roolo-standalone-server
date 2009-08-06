import roolo.elo.ELOMetadataKeys;
import roolo.elo.MetadataTypeManager;
import roolo.elo.api.I18nType;
import roolo.elo.api.IMetadataKey;
import roolo.elo.api.IMetadataTypeManager;
import roolo.elo.api.metadata.MetadataTokenization;
import roolo.elo.api.metadata.MetadataValueCount;
import roolo.elo.metadata.keys.StringMetadataKey;


public class MetadataUtil {
	public static IMetadataTypeManager<IMetadataKey> createTypeManager(){
		IMetadataTypeManager<IMetadataKey> typeManager = new MetadataTypeManager<IMetadataKey>();
		
		typeManager.registerMetadataKey(ELOMetadataKeys.URI.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.TYPE.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.VERSION.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.TITLE.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.AUTHOR.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.SUBJECT.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.GRADELEVEL.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.FAMILYTAG.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.ISCURRENT.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.DATE_CREATED.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.KEYWORDS.getKey());
		typeManager.registerMetadataKey(ELOMetadataKeys.COMMENT.getKey());
		
		/** 
		 * Metadata keys specific to S3
		 */
		IMetadataKey relatedTo = new StringMetadataKey ("relatedto", "/relatedto", I18nType.UNIVERSAL, MetadataValueCount.SINGLE, MetadataTokenization.UNTOKENIZED, null);
		IMetadataKey contributors = new StringMetadataKey ("contributors", "/contributors", I18nType.UNIVERSAL, MetadataValueCount.SINGLE, MetadataTokenization.UNTOKENIZED, null);
		
		typeManager.registerMetadataKey(relatedTo);
		typeManager.registerMetadataKey(contributors);
		
		return typeManager;
	}
}
