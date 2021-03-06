<?php namespace PCRecruiter;

/**
 * Class Candidates
 * @package PCRecruiter
 */
class Candidates extends Client
{
    /**
     * Search Candidate Records
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   null|string $id
     * @param   array $parameters
     * @example $parameters = array('Query' => 'some_query', 'Custom' => 'some_field', 'ResultsPerPage' => '10')
     * @return  array|false
     */
    public function get($id = null, $parameters = [])
    {
        $endpoint = '/candidates';

        if (!empty($id))
            $endpoint .= '/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   array $candidate - Array with candidate descriptions
     * @return  array|false
     */
    public function create($candidate = [])
    {
        $endpoint = '/candidates';

        return $this->doRequest('post', $endpoint, array('Candidate' => $candidate));
    }

    /**
     * Update a candidate details by id
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   null|string $id
     * @param   array $candidate - Array with candidate descriptions
     * @return  array|false
     */
    public function update($id, $candidate = [])
    {
        $endpoint = '/candidates/' . $id;

        return $this->doRequest('put', $endpoint, array('Candidate' => $candidate));
    }

    /**
     * Delete Candidate by Id
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @return  array|false
     */
    public function delete($id)
    {
        $endpoint = '/candidates/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('delete', $endpoint);
    }

    /**
     * Get Attachments by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $cid CandidateId
     * @param   null|string $aid AttachmentId
     * @param   array $parameters
     * @return  array|false
     */
    public function getAttachments($cid, $aid = null, $parameters = [])
    {
        $endpoint = '/candidates/' . $cid . '/attachments';

        if (!empty($ait))
            $endpoint .= '/' . $aid;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Attachment
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $cid CandidateId
     * @param   null|string $aid AttachmentId
     * @param   array $attachments
     * @return  array|false
     */
    public function createAttachments($cid, $aid = null, $attachments = [])
    {
        $endpoint = '/candidates/' . $cid . '/attachments';

        if (!empty($aid))
            $endpoint .= '/' . $aid;

        return $this->doRequest('post', $endpoint, array('Attachments' => $attachments));
    }

    /**
     * Update a Candidate Attachment
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $cid CandidateId
     * @param   string $aid AttachmentId
     * @param   array $attachments Candidate Attachments Body
     * @return  array|false
     */
    public function updateAttachments($cid, $aid, $attachments = [])
    {
        $endpoint = '/candidates/' . $cid . '/attachments/' . $aid;

        return $this->doRequest('put', $endpoint, array('Attachments' => $attachments));
    }

    /**
     * Search Candidate Activities by CandidateId
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getActivities($id, $parameters = [])
    {
        $endpoint = '/candidates/' . $id . '/activities';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Activity
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $activity
     * @return  array|false
     */
    public function createActivities($id, $activity = [])
    {
        $endpoint = '/candidates/' . $id . '/activities';

        return $this->doRequest('post', $endpoint, array('CandidateActivity' => $activity));
    }

    /**
     * Return a Candidate's Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getResumes($id, $parameters = [])
    {
        $endpoint = '/candidates/' . $id . '/resumes';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function createResumes($id, $resume = [])
    {
        $endpoint = '/candidates/' . $id . '/resumes';

        return $this->doRequest('post', $endpoint, array('Resume' => $resume));
    }

    /**
     * Update Candidate Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function updateResumes($id, $resume = [])
    {
        $endpoint = '/candidates/' . $id . '/resumes';

        return $this->doRequest('put', $endpoint, array('Resume' => $resume));
    }

    /**
     * Return a Candidate's Blinded Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $parameters
     * @return  array|false
     */
    public function getBlinded($id, $parameters = [])
    {
        $endpoint = '/candidates/' . $id . '/blindedresumes';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Create a new Candidate Blinded Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function createBlinded($id, $resume = [])
    {
        $endpoint = '/candidates/' . $id . '/blindedresumes';

        return $this->doRequest('post', $endpoint, array('Resume' => $resume));
    }

    /**
     * Update Candidate Blinded Resume
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   string $id CandidateId
     * @param   array $resume
     * @return  array|false
     */
    public function updateBlinded($id, $resume = [])
    {
        $endpoint = '/candidates/' . $id . '/blindedresumes';

        return $this->doRequest('put', $endpoint, array('Resume' => $resume));
    }

    /**
     * Search Candidate Activities
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   null|string $id ActivityId
     * @param   array $parameters
     * @return  array|false
     */
    public function getActivitiesAll($id = null, $parameters = [])
    {
        $endpoint = '/candidates/activities';

        if (!empty($id))
            $endpoint .= '/' . $id;

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

    /**
     * Get Distinct Fields
     *
     * @link    https://www.pcrecruiter.net/APIDOCS_V2/json/candidates.json
     * @param   array $parameters
     * @return  array|false
     */
    public function getDistinctFields($parameters = [])
    {
        $endpoint = '/candidates/DistinctFields';

        if (!empty($parameters) && is_array($parameters))
            $endpoint .= $this->compileURL($parameters);

        return $this->doRequest('get', $endpoint);
    }

}
