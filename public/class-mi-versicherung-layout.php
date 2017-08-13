<?php


/**
 * Methods fpr Rendering the single-page Versicherung
 * Replacing and hiding content blocks
 *
 * @since      1.0.0
 * @package    Mi_Tarifrechner
 * @subpackage Mi_Tarifrechner/includes
 * @author     Michael Mai <mmai.mai.internet@gmail.com>
 */
class Mi_VersicherungLayout {

	/**
	 * @var null|WP_Post
	 */
	protected $post = null;

	private $id_of_div_block_tarifrechner = 'mi-tarifrechner-call';
	private $id_of_div_block_video = 'mi-videos';

	public function __construct( WP_Post $post = null ) {
		if ( $post === null ) {
			global $post;
		}
		$this->post = $post;
	}

	public function render() {
		$args     = array(
			'post_type'      => 'fl-builder-template',
			'name'           => 'mi-versicherung-template',
			'posts_per_page' => '1'
		);
		$wp_query = new WP_Query( $args );
		if ( $wp_query->have_posts() ) {
			ob_start();
			FLBuilder::render_query( $args );
			$html = ob_get_clean();
			$html = $this->replaceHeader( $html );
			$html = $this->replaceDownload( $html );
			$html = $this->replaceTarifrechner( $html );
			$html = $this->replaceVideos( $html );
			$html = $this->replaceBackgroundImage($html);
			return $html;
		} else {
			return $this->renderNotFound();
		}
	}

	protected function replaceHeader( $layout ) {
		// Titel:
		$title  = get_the_title( $this->post->ID );
		$layout = str_replace( array( '##Titel##', '##Title##' ), $title, $layout );
		// Untertitel:
		$untertitel = get_field( 'untertitel', $this->post->ID );
		$layout     = str_replace( array( '##Untertitel##' ), $untertitel, $layout );
		// Einleitung:
		$content = get_post_field( 'post_content', $this->post );
		$layout  = str_replace( array( '##Einleitender Text##' ), $content, $layout );

		return $layout;
	}

	protected function replaceDownload( $layout ) {
		$mi_item           = '<div data-animation-delay="0.0">
<div class="">
<span class="fl-icon-wrap">
<span class="fl-icon"><a href="%s" target="_self"><i class="fa fa-file-pdf-o"></i> </a></span>
<span class="fl-icon-text"><a href="%s" target="_self">%s (%s)</a></span>
</span>
</div>
</div>';
		$list_of_downloads = '';
		while ( have_rows( 'broschuere', $this->post->ID ) ) {
			the_row();
			$titel             = get_sub_field( 'broschuere_titel' );
			$id                = get_sub_field( 'broschuere_id' );
			$url               = wp_get_attachment_url( $id );
			$filesize          = filesize( get_attached_file( $id ) );
			$filesize          = size_format( $filesize, 2 );
			$link              = sprintf(
				$mi_item,
				$url,
				$url,
				$titel,
				$filesize
			);
			$list_of_downloads .= ( $link . '<br>' );
		}

		return str_replace( '##Downloads##', $list_of_downloads, $layout );
	}

	protected function replaceTarifrechner( $layout ) {
		// Titel:
		if ( Mi_Versicherung::hasTarifrechner( $this->post ) ) {
			$url = MI_Versicherung::mi_get_url_tarifrechner_call( $this->post->post_name );
			return str_replace( '##Tarifrechner_URL##', $url, $layout );
		} else {
			$layout = $this->removeDivFromDom( $layout, $this->id_of_div_block_tarifrechner );
			return $layout;
		}
	}

	protected function replaceVideos( $layout ) {
		if (Mi_Versicherung::hasVideos($this->post)) {
			$video_url = get_field( 'video_url', $this->post->ID );
			if ( strpos( $video_url, '<iframe' ) === false ) {
				$video_url = sprintf( '<iframe width="600" height="550" src="%s"></iframe>', $video_url );
			}
			return str_replace( '##Videos##', $video_url, $layout );
		} else {
			return $this->removeDivFromDom( $layout, $this->id_of_div_block_video );
		}
	}

	private function removeDivFromDom( $dom, $id ) {
		if ( $id == '' ) {
			// case for preventing removing the whole html
			return $dom;
		} else {
			// flags are important here (s = within linebreaks , U = ungreedy):
			return str_replace('id="'.$id.'"','id="'.$id.'" style="display:none;visibility:hidden;"' , $dom);
//			$re = '/<div id="' . $id . '".+>.+<\/div>/sU';
//			$replacement = preg_replace( $re, '', $dom );
////			$matches = array();
////			preg_match_all($re, $dom, $matches, PREG_SET_ORDER, 0);
////			$a = $matches;
//			return $replacement;
		}
	}

	private function replaceBackgroundImage($html) {
		$old = 'mi-versicherung-header';
		if (Mi_Versicherung::isPrivatkunde($this->post)) {
			$new_id = 'mi-versicherung-header-pk';
		} else {
			$new_id = 'mi-versicherung-header-gk';
		}
		$html = str_replace($old, $new_id, $html);
		return $html;
	}


	public function renderNotFound() {
		$html = '<div class="alert">Leider ist ein Fehler aufgetreten.<br>
			Die angeforderten Informationen konnten nicht geladen werden.<br><a href=".">Zur Startseite</a> 
			</div>';

		return $html;
	}


}
